<?php

namespace App\Services\Survey\Report;

use App\Contracts\Survey\Report\SurveyReportContract;
use App\Data\Survey\Report\SurveyReportData;
use App\Models\Student;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyGroupAssignment;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyResult;
use Illuminate\Support\Collection;

class KakSToboyObrashSurveyReport implements SurveyReportContract
{
    public function supports(Survey $survey): bool
    {
        return str_contains($survey->getTranslation('title', 'ru'), 'Как с тобой обращаются')
            || str_contains($survey->getTranslation('title', 'kk'), 'Саған қалай қарайды');
    }

    public function build(Collection $scalingData, array $options = []): SurveyReportData
    {
        $isWholeSchool = ($options['classroom_id'] == 0);

        $surveyId    = $options['survey_id'];
        $classroomId = $options['classroom_id'];

        // dd('BUILD STARTED', $surveyId, $classroomId);



        // 1. Вопросы + варианты
        $questions = SurveyQuestion::where('survey_id', $surveyId)
            ->orderBy('number')
            ->with(['options' => fn($q) => $q->orderBy('sort')])
            ->get();

        if ($isWholeSchool) {

            $organizationId = $options['organization_id'];
            $surveyId = $options['survey_id'];

            //
            // 1. Все группы данного опроса в этой школе
            //
            $groups = SurveyGroupAssignment::where('survey_id', $surveyId)
                ->where('organization_id', $organizationId)
                ->get();

            //
            // 2. Assignments всех учеников, проходивших опрос
            //
            $assignments = SurveyAssignment::whereIn('group_id', $groups->pluck('id'))
                ->get()
                ->groupBy('student_id'); // важно!

            //
            // 3. Студенты, которые реально участвовали
            //
            $students = Student::whereIn('id', $assignments->keys())
                ->orderBy('classroom_id')
                ->get()
                ->groupBy('classroom_id'); // группировка по классу

            //
            // 4. Классы, в которых есть такие студенты
            //
            $classrooms = \App\Models\Classroom::whereIn('id', $students->keys())
                ->orderBy('grade')
                ->orderBy('letter')
                ->get();

            //
            // 5. Все результаты этих assignments
            //
            $results = SurveyResult::whereIn(
                'survey_assignment_id',
                $assignments->flatten()->pluck('id')
            )
                ->get()
                ->groupBy('survey_assignment_id');

            // ---------- ФОРМИРУЕМ ОТЧЁТ ПО ШКОЛЕ ----------

            $wholeSchoolReport = [];

            foreach ($questions as $question) {

                $qRow = [
                    'number'  => $question->number,
                    'text'    => $question->getTranslation('title', app()->getLocale()),
                    'answers' => [],
                ];

                foreach ($question->options as $option) {

                    $classTotals = [];

                    foreach ($classrooms as $classroom) {

                        $classStudents = $students[$classroom->id] ?? collect();
                        $count = 0;

                        foreach ($classStudents as $st) {

                            // one assignment per student
                            $sa = ($assignments[$st->id] ?? collect())->first();
                            if (!$sa) continue;

                            $saResults = $results[$sa->id] ?? collect();

                            if (
                                $saResults
                                ->where('question_id', $question->id)
                                ->where('option_id', $option->id)
                                ->isNotEmpty()
                            ) {
                                $count++;
                            }
                        }

                        // сумма по данному классу
                        $classTotals[] = $count;
                    }

                    $qRow['answers'][] = [
                        'text'  => $option->getTranslation('title', app()->getLocale()),
                        'marks' => $classTotals,
                        'total' => array_sum($classTotals),
                    ];
                }

                $wholeSchoolReport[] = $qRow;
            }

            return new SurveyReportData(
                reportData: [
                    'classrooms' => $classrooms,
                    'questions'  => $wholeSchoolReport,
                ],
                exportClass: \App\Exports\Survey\KakSToboyObrashExcelExportOrganization::class,
                exportTitle: Survey::find($surveyId)->getTranslation('title', app()->getLocale()),
                bladeView: 'how-you-treated-whole-school'
            );
        }


        // 2. Ученики класса (в порядке ID → стабильный порядок)
        $students = Student::where('classroom_id', $classroomId)
            ->where('organization_id', $options['organization_id'])
            ->orderBy('id')
            ->get();

        // 3. Группы (обычно одна)
        $groups = SurveyGroupAssignment::where('survey_id', $surveyId)
            ->where('organization_id', $options['organization_id'])
            ->where('classroom_id', $classroomId)
            ->get();

        // 4. Assignments (один студент → одна попытка)
        $assignments = SurveyAssignment::whereIn('group_id', $groups->pluck('id'))
            ->get()
            ->keyBy('student_id'); // важно

        // 5. Все результаты
        $results = SurveyResult::whereIn('survey_assignment_id', $assignments->pluck('id'))
            ->get()
            ->groupBy('survey_assignment_id');

        // ------------------------------------------------------------------

        // 🔥 Формируем финальный отчёт
        $report = [];

        foreach ($questions as $question) {

            $questionRow = [
                'number' => $question->number,
                'text' => $question->getTranslation('title', app()->getLocale()),
                'answers' => [],
            ];

            foreach ($question->options as $option) {

                $marks = [];

                foreach ($students as $student) {

                    $assignment = $assignments[$student->id] ?? null;
                    $value = '';

                    if ($assignment) {
                        $studentResults = $results[$assignment->id] ?? collect();

                        $value = $studentResults
                            ->where('question_id', $question->id)
                            ->where('option_id', $option->id)
                            ->isNotEmpty()
                            ? '1'
                            : '';
                    }

                    $marks[] = $value; // по порядку, а не по student_id
                }

                $questionRow['answers'][] = [
                    'text' => $option->getTranslation('title', app()->getLocale()),
                    'marks' => $marks,
                    'total' => collect($marks)->filter(fn($v) => $v === '1')->count()
                ];
            }

            $report[] = $questionRow;
        }

        // ---------------------------
        // 🔥 СЧИТАЕМ ИТОГИ В КОНЦЕ
        // ---------------------------

        // сумма по каждому ученику (количество "1")
        $totalsPerStudent = [];

        foreach ($students as $idx => $student) {
            $totalsPerStudent[$idx] = 0;
        }

        // суммарное количество всех ответов
        $totalAll = 0;

        // пробегаем по вопросам и вариантам
        foreach ($report as $q) {
            foreach ($q['answers'] as $a) {
                foreach ($a['marks'] as $i => $mark) {
                    if ($mark === '1') {
                        $totalsPerStudent[$i]++;
                        $totalAll++;
                    }
                }
            }
        }


        return new SurveyReportData(
            reportData: [
                'students'  => $students,
                'questions' => $report,
                'totals_per_student' => $totalsPerStudent,
                'total_all'          => $totalAll,
            ],
            exportClass: \App\Exports\Survey\KakSToboyObrashExcelExportClassroom::class,
            exportTitle: Survey::find($surveyId)->getTranslation('title', app()->getLocale()),
            bladeView: 'how-you-treated'
        );
    }
}
