import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/chat.js'],
      refresh: true,
    }),
    vue(),
  ],
  resolve: {
    alias: {
      'vue': path.resolve(__dirname, 'node_modules/vue/dist/vue.esm-bundler.js'),
    },
  },
  build: {
    outDir: 'public/build',
  },
});
