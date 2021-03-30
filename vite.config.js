import { defineConfig } from 'vite'
import reactRefresh from '@vitejs/plugin-react-refresh'
import { resolve } from 'path'

const twigRefreshPlugin = {
  name: 'twig-refresh',
  configureServer({ watcher, ws }) {
    watcher.add(resolve("templates/**/*.twig"));
    watcher.on("change", function (path) {
      if (path.endsWith(".twig")) {
        ws.send({
          type: 'full-reload',
        })
      }
    });
  }
}

export default defineConfig({
  plugins: [reactRefresh(), twigRefreshPlugin],
  root: './assets',
  base: '/assets/',
  server: {
    watch: {
      disableGlobbing: false,
    }
  },
  build: {
    manifest: true,
    assetsDir: '',
    outDir: '../public/assets/',
    rollupOptions:{
      input:{
        'main.jsx': './assets/App.js'
      }
    }
  }
})


