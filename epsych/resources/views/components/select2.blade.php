@props([
    'name',
    'options',
    'live' => false,
    'watch' => false,
    'multiple' => false,
    'disabled' => false
])

<div
	wire:ignore

	x-data="{
		options: $wire.$entangle('{{ $options }}'),
		current: $wire.$entangle('{{ $name }}'),
	}"

	x-init="
        if (@json($disabled)) {
            $refs.select2.disabled = true;
        }
		$wire.on('update', function () {
			$($refs['select2']).val(null).trigger('change');
		});
		if(!$($refs['select2']).data('select2')) {
			init();
		}
		$wire.on('set-options', function () {
			init();
		});
		$wire.on('cancel-offcanvas', function () {
			resetSelect2();
		});

		function init() {
			options = $wire.get('{{ $options }}');
			current = $wire.get('{{ $name }}');

			if({{ $watch ? 'true' : 'false' }}) {
				$watch('options', (value, old) => {
					if(value !== old) {
						$($refs['select2']).select2().empty();
						updateSelect2(options);
					}
				});
			}
			updateSelect2(options);
		}

		function resetSelect2() {
			if ($($refs['select2']).data('select2')) {
				$($refs['select2']).val(null).trigger('change');
			}
		}

		function updateSelect2(data) {
			resetSelect2();

			let selectData = Object.entries(data).map(([id, text]) => ({ id, text }));

			$($refs['select2']).append(new Option('', '', false, false));

			$($refs['select2']).select2({
				placeholder: '{{ __('Выберите значение') }}',
				dropdownAutoWidth: true,
				dropdownCssClass: 'select2-dropdown--below',
				data: selectData,
				language: {
					noResults: function() {
				  		return '{{__('Нет результатов')}}';
					}
			  	}
			}).val(current).trigger('change').on('change', function () {
				$wire.set('{{ $name }}', $(this).val(), '{{ $live }}');
			});
		}
 	"
>

	<select name="{{ $name }}"
			x-ref="select2"
			@class([
				'js-select2',
				'js-select2-multiple' => $multiple,
				$attributes->get('class')
			])
			{{ $multiple ? 'multiple' : '' }}>
		<option></option>
	</select>

</div>
