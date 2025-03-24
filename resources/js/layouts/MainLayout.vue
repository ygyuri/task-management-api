<script setup>
import { ref } from "vue";
import { MenuUnfoldOutlined, MenuFoldOutlined, HomeOutlined, AppstoreOutlined, UserOutlined } from "@ant-design/icons-vue";
import { useRouter } from "vue-router";

const collapsed = ref(false); // Controls sidebar collapse
const router = useRouter();

// Toggle sidebar collapse
const toggleSidebar = () => {
  collapsed.value = !collapsed.value;
};

// Logout function (placeholder)
const logout = () => {
  console.log("Logging out...");
};
</script>

<template>
  <a-layout style="min-height: 100vh">
    
    <!-- Sidebar (Sider) -->
    <a-layout-sider v-model="collapsed" collapsible class="sidebar">
        <div class="logo"> Task  Management </div> <!-- Branding -->
      <a-menu theme="dark" mode="inline" @click="({ key }) => router.push(key)">
        <!-- <a-menu-item key="/">
          <home-outlined />
          <span>Home</span>
        </a-menu-item> -->
        <a-menu-item key="/tasks">
          <appstore-outlined />
          <span>Tasks</span>
        </a-menu-item>
        <!-- <a-menu-item key="/profile">
          <user-outlined />
          <span>Profile</span>
        </a-menu-item> -->
      </a-menu>
    </a-layout-sider>

    <!-- Main Layout -->
    <a-layout>
      
      <!-- Navbar -->
      <a-layout-header class="header">
        <a-button type="text" @click="toggleSidebar" class="menu-toggle">
          <menu-unfold-outlined v-if="collapsed" />
          <menu-fold-outlined v-else />
        </a-button>
        <div class="header-right">
          <a-avatar size="large" icon="user" />
          <!-- <a-button type="primary" danger @click="logout">Logout</a-button> -->
        </div>
      </a-layout-header>

      <!-- Main Content -->
      <a-layout-content class="content">
        <router-view /> <!-- This is where dynamic page content will be loaded -->
      </a-layout-content>

    </a-layout>

  </a-layout>
</template>

<style scoped>
/* Sidebar */
.sidebar {
  background: #001529;
}
.logo {
  height: 64px;
  line-height: 64px;
  color: white;
  text-align: center;
  font-size: 20px;
  font-weight: bold;
  background: rgba(255, 255, 255, 0.2);
}

/* Navbar */
.header {
  background: #fff;
  padding: 0 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.menu-toggle {
  font-size: 18px;
}

/* Main Content */
.content {
  padding: 24px;
  background: #f0f2f5;
  min-height: 100vh;
}

/* Header Right (Avatar & Logout) */
.header-right {
  display: flex;
  align-items: center;
  gap: 15px;
}
</style>
