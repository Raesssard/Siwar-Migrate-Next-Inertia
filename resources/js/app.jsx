import './bootstrap'
import 'bootstrap-icons/font/bootstrap-icons.css'
import '../css/app.css'
import React from 'react'
import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'
import Layout from './Layouts/Layout'

createInertiaApp({
  title: title => title ? `${title} - SIWAR` : 'SIWAR',
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
    const page = pages[`./Pages/${name}.jsx`]

    if (!page) {
      throw new Error(`Page ${name} not found`)
    }

    // Hanya halaman Login / ChooseRole yang tidak pakai layout
    if (name === 'Login' || name === 'ChooseRole') {
      page.default.layout = undefined
    } else {
      page.default.layout = page.default.layout || ((page) => <Layout>{page}</Layout>)
    }

    return page
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />)
  },
})