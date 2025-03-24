import { createRouter, createWebHistory } from "vue-router";
import MainLayout from "@/layouts/MainLayout.vue";
import TaskManager from "@/components/TaskManager.vue";
//import UserProfile from "@/components/UserProfile.vue";

const routes = [
  {
    path: "/",
    component: MainLayout,
    children: [
      { path: "", component: TaskManager },
      { path: "tasks", component: TaskManager },
      //{ path: "profile", component: UserProfile },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
