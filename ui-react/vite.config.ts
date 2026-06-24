import { defineConfig } from 'vite'
import path from 'node:path'

/**
 * Build for the MelisCmsCategory2 React brick.
 *
 * Single IIFE bundle (public/ui-react/brick.js) loaded at runtime by the MelisCore React shell
 * when the module is active. React / ReactRouter are EXTERNAL (mapped to the host globals exposed
 * in MelisCore's main.tsx) so the brick reuses the host React instance (hooks/context/Router).
 */
export default defineConfig({
  esbuild: { jsx: 'automatic' },
  build: {
    minify: false,
    outDir: path.resolve(import.meta.dirname, '..', 'public', 'ui-react'),
    emptyOutDir: false,
    lib: {
      entry: path.resolve(import.meta.dirname, 'src/brick.tsx'),
      formats: ['iife'],
      name: 'MelisCmsCategory2Brick',
      fileName: () => 'brick.js',
    },
    rollupOptions: {
      external: ['react', 'react-dom', 'react/jsx-runtime', 'react-router-dom'],
      output: {
        globals: {
          react: 'MelisReact',
          'react-dom': 'MelisReactDOM',
          'react/jsx-runtime': 'MelisReactJsxRuntime',
          'react-router-dom': 'MelisReactRouterDOM',
        },
      },
    },
  },
})
