import LayoutDefault from './LayoutFullWidth.vue';

export default {
    name: 'LayoutDefaultDynamic',
    created() {
        this.$parent.$emit('update:layout', LayoutDefault);
    },
    render() {
        return this.$slots.default[0];
    },
};

