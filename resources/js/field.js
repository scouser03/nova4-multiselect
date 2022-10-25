import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-nova4-multiselect', IndexField)
  app.component('detail-nova4-multiselect', DetailField)
  app.component('form-nova4-multiselect', FormField)
})
