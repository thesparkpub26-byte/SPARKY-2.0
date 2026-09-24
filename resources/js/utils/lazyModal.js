import { defineComponent, h, markRaw, nextTick, ref, shallowRef, watch } from 'vue';

// A modal whose code is downloaded the first time someone opens it, not with the page around it.
//
//   const AssignTaskModal = lazyModal(() => import('../components/AssignTaskModal.vue'));
//
// Used exactly like the modal itself (same props, same events). Our modals react to their open prop
// *changing*, so the real modal is first mounted closed and then opened one tick later; mounting it
// already open would skip its "just opened" setup.
export const lazyModal = (loader, openProp = 'isOpen') => defineComponent({
    name: 'LazyModal',
    inheritAttrs: false,
    props: { [openProp]: { type: Boolean, default: false } },
    setup(props, { attrs, slots }) {
        const component = shallowRef(null);
        const innerOpen = ref(false);
        let loading = false;

        const load = async () => {
            if (component.value || loading) return;
            loading = true;
            try {
                component.value = markRaw((await loader()).default);
                await nextTick(); // the modal is now mounted, closed
                innerOpen.value = props[openProp];
            } catch (error) {
                console.error('Could not load the dialog.', error); // retried the next time it is opened
            } finally {
                loading = false;
            }
        };

        watch(() => props[openProp], (open) => {
            if (component.value) innerOpen.value = open;
            else if (open) load();
        }, { immediate: true });

        return () => component.value
            ? h(component.value, { ...attrs, [openProp]: innerOpen.value }, slots)
            : null;
    },
});
