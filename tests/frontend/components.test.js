import { mount } from '@vue/test-utils';
import { describe, expect, it, vi, beforeEach } from 'vitest';
import StatusIndicator from '../../resources/js/components/StatusIndicator.vue';
import LoginForm from '../../resources/js/components/LoginForm.vue';

const { push, post } = vi.hoisted(() => ({ push: vi.fn(), post: vi.fn() }));

vi.mock('vue-router', () => ({
  useRouter: () => ({ push }),
}));

vi.mock('axios', () => ({
  default: { post },
}));

describe('StatusIndicator', () => {
  it('renders known statuses with semantic classes', () => {
    const wrapper = mount(StatusIndicator, { props: { status: 'published' } });
    expect(wrapper.text()).toBe('published');
    expect(wrapper.classes()).toContain('status-published');
    expect(wrapper.attributes('aria-label')).toBe('Status: published');
  });

  it('falls back safely for unknown statuses', () => {
    const wrapper = mount(StatusIndicator, { props: { status: 'something-new' } });
    expect(wrapper.text()).toBe('Unknown');
    expect(wrapper.classes()).toContain('status-unknown');
  });
});

describe('LoginForm', () => {
  beforeEach(() => {
    push.mockReset();
    post.mockReset();
    localStorage.clear();
  });

  it('keeps submit disabled until the credentials are filled', async () => {
    const wrapper = mount(LoginForm);
    const button = wrapper.get('button[type="submit"]');
    expect(button.element.disabled).toBe(true);
    await wrapper.get('#email').setValue('person@example.com');
    await wrapper.get('#password').setValue('secret');
    expect(button.element.disabled).toBe(false);
  });

  it('stores the session and routes to the dashboard after login', async () => {
    post.mockResolvedValue({ data: { access_token: 'token-123', user: { id: 7, name: 'Person Example' } } });
    const wrapper = mount(LoginForm);
    await wrapper.get('#email').setValue('person@example.com');
    await wrapper.get('#password').setValue('secret');
    await wrapper.get('form').trigger('submit.prevent');
    await new Promise((resolve) => setTimeout(resolve, 0));

    expect(post).toHaveBeenCalledWith('/api/login', { email: 'person@example.com', password: 'secret' });
    expect(localStorage.getItem('token')).toBe('token-123');
    expect(push).toHaveBeenCalledWith('/dashboard');
  });
});
