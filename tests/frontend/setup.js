import { vi } from 'vitest';

Object.defineProperty(window, 'confirm', { writable: true, value: vi.fn(() => true) });
Object.defineProperty(window, 'alert', { writable: true, value: vi.fn() });
