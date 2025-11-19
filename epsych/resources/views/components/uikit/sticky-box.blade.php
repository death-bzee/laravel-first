@props(['offsetBottom' => false])

<div x-data="stickyComponent()" x-init="init()" @scroll.window="handleScroll">
    <div :class="{'sticky top-10': isSticky, 'absolute bottom-0': !isSticky}">
        {{ $slot }}
    </div>
</div>

<script>
    function stickyComponent() {
        return {
            isSticky: true,
            footer: null,
            init() {
                this.footer = document.querySelector('footer');
            },
            handleScroll() {
            const footerRect = this.footer.getBoundingClientRect()
                const stickyRect = this.$el.getBoundingClientRect();

                this.isSticky = !(stickyRect.bottom > footerRect.top);
            }
        };
    }
</script>
