/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// import Vue from 'vue'
import { createApp } from 'vue'
// import VueRouter from 'vue-router';
import { createRouter, createWebHistory } from 'vue-router'
// import TextareaAutosize from 'vue-textarea-autosize'
// import sanitizeHTML from 'sanitize-html'
import Vue3Sanitize from "vue-3-sanitize";
// import VModal from 'vue-js-modal'
// import VuejsDialog from 'vuejs-dialog';
// import 'vuejs-dialog/dist/vuejs-dialog.min.css';


//import store from './store'
import store from "./store/index.js";
import SystemError from './components/e_learning2/errors/System.vue'
import NotFound from './components/e_learning2/errors/NotFound.vue'
import E_learning2_App from './E_learning2_App.vue'
import E_learning2LoginComponent from "./components/e_learning2/E_learning2LoginComponent";
import E_learning2TcComponent from "./components/e_learning2/E_learning2TcComponent";
import E_learning2TcRootComponent from "./components/e_learning2/E_learning2TcRootComponent";
import E_learning2QuestionListComponent from "./components/e_learning2/E_learning2QuestionListComponent";
import E_learning2QuestionShowComponent from "./components/e_learning2/E_learning2QuestionShowComponent";
import E_learning2QuestionCreateComponent from "./components/e_learning2/E_learning2QuestionCreateComponent";
import E_learning2QuestionEditComponent from "./components/e_learning2/E_learning2QuestionEditComponent";
import E_learning2QuestionCsvComponent from "./components/e_learning2/E_learning2QuestionCsvComponent";
import E_learning2QuestionSettingComponent from "./components/e_learning2/E_learning2QuestionSettingComponent";
import E_learning2GroupListComponent from "./components/e_learning2/E_learning2GroupListComponent";
import E_learning2GroupCreateComponent from "./components/e_learning2/E_learning2GroupCreateComponent";
import E_learning2GroupEditComponent from "./components/e_learning2/E_learning2GroupEditComponent";
import E_learning2OwnerListComponent from "./components/e_learning2/E_learning2OwnerListComponent";
import E_learning2ClassListComponent from "./components/e_learning2/E_learning2ClassListComponent";
import E_learning2ClassCreateComponent from "./components/e_learning2/E_learning2ClassCreateComponent";
import E_learning2ClassEditComponent from "./components/e_learning2/E_learning2ClassEditComponent";
import E_learning2MemberListComponent from "./components/e_learning2/E_learning2MemberListComponent";
import E_learning2MemberRecordComponent from "./components/e_learning2/E_learning2MemberRecordComponent";
import E_learning2StComponent from "./components/e_learning2/E_learning2StComponent";
import E_learning2StQuestionComponent from "./components/e_learning2/E_learning2StQuestionComponent";
import E_learning2ClassJoinComponent from "./components/e_learning2/E_learning2ClassJoinComponent";
import E_learning2AdComponent from "./components/e_learning2/E_learning2AdComponent";
import E_learning2UserListComponent from "./components/e_learning2/E_learning2UserListComponent";
import E_learning2UserCreateComponent from "./components/e_learning2/E_learning2UserCreateComponent";
import E_learning2UserEditComponent from "./components/e_learning2/E_learning2UserEditComponent";
import E_learning2UserCsvComponent from "./components/e_learning2/E_learning2UserCsvComponent";

require('./bootstrap');

window.Vue = require('vue').default;

// Vue.use(VueRouter);
// Vue.use(TextareaAutosize);
// Vue.use(VModal);
// Vue.use(VuejsDialog, {
//   html: true,
//   loader: true,
//   okText: 'OK',
//   cancelText: 'Cancel',
//   animation: 'bounce'
// });

// const router = new VueRouter({
//   mode: 'history',
const router = createRouter({
  history: createWebHistory(),
  scrollBehavior () {
    return { left: 0, top: 0 }
    },
  routes: [
    {
      path: '/e_learning2/login',
      component: E_learning2LoginComponent,
      beforeEnter (to, from, next) {
        if (store.getters['auth_e_learning2/check'] && store.getters['auth_e_learning2/role'] == 5) {
          next('/e_learning2/tc')
        } else if (store.getters['auth_e_learning2/check'] && store.getters['auth_e_learning2/role'] == 10) {
          next('/e_learning2/st')
        } else if (store.getters['auth_e_learning2/check'] && store.getters['auth_e_learning2/role'] == 1) {
          next('/e_learning2/ad')
        } else {
          next()
        }
      }
    },
    {
      path: '/e_learning2/tc',
      component: E_learning2TcComponent,
        children: [
          {
            path: '',
            component: E_learning2TcRootComponent
          },
          {
            path: 'question',
            name: 'tc.questionlist',
            component: E_learning2QuestionListComponent
          },
          {
            path: 'question/:questionId',
            name: 'tc.questionshow',
            component: E_learning2QuestionShowComponent,
            props: true
          },
          {
            path: 'question/create',
            name: 'tc.questioncreate',
            component: E_learning2QuestionCreateComponent
          },
          {
            path: 'question/import',
            name: 'tc.questionimport',
            component: E_learning2QuestionCsvComponent
          },
          {
            path: 'question/:questionId',
            name: 'tc.questionedit',
            component: E_learning2QuestionEditComponent,
            props: true
          },
          {
            path: 'group/list',
            name: 'tc.grouplist',
            component: E_learning2GroupListComponent
          },
          {
            path: 'group/create',
            name: 'tc.groupcreate',
            component: E_learning2GroupCreateComponent
          },
          {
            path: 'group/:groupId',
            name: 'tc.groupedit',
            component: E_learning2GroupEditComponent,
            props: true
          },
          {
            path: 'owner_list',
            name: 'tc.owner',
            component: E_learning2OwnerListComponent,
            props: true
          },
          {
            path: 'class/list/:groupId',
            name: 'tc.classlist',
            component: E_learning2ClassListComponent,
            props: true
          },
          {
            path: 'class/create/:groupId',
            name: 'tc.classcreate',
            component: E_learning2ClassCreateComponent,
            props: true
          },
          {
            path: 'class/:groupId',
            name: 'tc.classedit',
            component: E_learning2ClassEditComponent,
            props: true
          },
          {
            path: 'member_list',
            name: 'tc.member',
            component: E_learning2MemberListComponent
          },
          {
            path: 'member_record',
            name: 'tc.record',
            component: E_learning2MemberRecordComponent
          },
          {
            path: 'join',
            name: 'tc.join',
            component: E_learning2ClassJoinComponent
          },
          {
            path: 'setting',
            name: 'tc.setting',
            component: E_learning2QuestionSettingComponent
          },
          {
            path: '/:pathMatch(.*)*',
            component: NotFound
          },
        ]
    },
    {
      path: '/e_learning2/st',
      component: E_learning2StComponent,
        children: [
          {
            path: '',
            name: 'st.question',
            component: E_learning2StQuestionComponent,
            props: true
          },
          {
            path: 'join',
            name: 'st.join',
            component: E_learning2ClassJoinComponent
          },
          {
            path: '/:pathMatch(.*)*',
            component: NotFound
          },
        ]
    },
    {
      path: '/e_learning2/ad',
      component: E_learning2AdComponent,
        children: [
          {
            path: '',
            name: 'ad.userlist',
            component: E_learning2UserListComponent
          },
          {
            path: 'usercreate',
            name: 'ad.usercreate',
            component: E_learning2UserCreateComponent
          },
          {
            path: 'userimport',
            name: 'ad.userimport',
            component: E_learning2UserCsvComponent
          },
          {
            path: ':userId',
            name: 'ad.useredit',
            component: E_learning2UserEditComponent,
            props: true
          },
          {
            path: '/:pathMatch(.*)*',
            component: NotFound
          },
        ]
    },
    {
      path: '/e_learning2/500',
      component: SystemError
    },
    {
      path: '/e_learning2/*',
      component: NotFound
    },
  ]
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// sanitizeHTML.defaults.allowedTags.push('img')
// sanitizeHTML.defaults.allowedAttributes.img = ['src']
// Vue.prototype.$sanitize = sanitizeHTML;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const createApp3 = async () => {
//   await store.dispatch('auth_e_learning2/currentUser')
//   new Vue({
//     el: '#app3',
//     router,
//     store,
//     components: { E_learning2_App }, // ルートコンポーネントの使用を宣言する
//     template: '<E_learning2_App />' // ルートコンポーネントを描画する
// });
// }
// createApp3()

const app = createApp(E_learning2_App)
app.use(router)
app.use(store)

const overridenOptions = {
  allowedTags: ['img']
};
app.use(Vue3Sanitize, overridenOptions);
app.mount('#app3')
