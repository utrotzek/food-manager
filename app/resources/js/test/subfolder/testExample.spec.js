import { mount } from '@vue/test-utils'
import ExampleComponent from '../../components/ExampleComponent.vue'

it('it works', () => {
    expect(1 + 1).toBe(2)
})

it('should mount without crashing', () => {
    const wrapper = mount(ExampleComponent)
    expect(wrapper).toMatchSnapshot()
})

it('should return 2', () => {
    const wrapper = mount(ExampleComponent)
    expect(wrapper.vm.returnTwo()).toEqual(2);
});
