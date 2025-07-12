<template>
  <div id="app">
    <!-- Navigation Bar -->
    <nav class="navbar">
      <div class="nav-container">
        <router-link to="/" class="nav-brand">
          <div class="brand-content">
            <span class="brand-icon">📋</span>
            <div class="brand-text">
              <div class="brand-title">ICIR System</div>
              <div class="brand-subtitle">Quality Inspection</div>
            </div>
          </div>
        </router-link>
        
        <div class="nav-menu">
          <router-link 
            to="/inspections" 
            class="nav-link"
            :class="{ active: $route.path === '/inspections' }"
          >
            <span class="nav-icon">📋</span>
            <span class="nav-text">Inspections</span>
          </router-link>
          <router-link 
            to="/inspections/create" 
            class="nav-link"
            :class="{ active: $route.path === '/inspections/create' }"
          >
            <span class="nav-icon">➕</span>
            <span class="nav-text">New Record</span>
          </router-link>
        </div>

        <!-- Mobile Menu Toggle -->
        <button 
          class="mobile-menu-toggle"
          @click="showMobileMenu = !showMobileMenu"
          :class="{ active: showMobileMenu }"
        >
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div class="mobile-menu" :class="{ show: showMobileMenu }">
        <router-link 
          to="/inspections" 
          class="mobile-nav-link"
          @click="showMobileMenu = false"
        >
          📋 Inspections
        </router-link>
        <router-link 
          to="/inspections/create" 
          class="mobile-nav-link"
          @click="showMobileMenu = false"
        >
          ➕ New Record
        </router-link>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-info">
          <p>&copy; 2024 Quality Inspection System. All rights reserved.</p>
          <p class="footer-version">Version 1.0.0</p>
        </div>
        <div class="footer-links">
          <a href="#" class="footer-link">Support</a>
          <a href="#" class="footer-link">Documentation</a>
        </div>
      </div>
    </footer>

    <!-- Loading Overlay -->
    <div v-if="loading" class="global-loading">
      <div class="loading-spinner"></div>
      <p>Loading...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useInspectionStore } from './stores/inspection'
import { storeToRefs } from 'pinia'

const inspectionStore = useInspectionStore()
const { loading } = storeToRefs(inspectionStore)

const showMobileMenu = ref(false)

// Close mobile menu when clicking outside
onMounted(() => {
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.navbar')) {
      showMobileMenu.value = false
    }
  })
})
</script>

<style>
/* Global Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen',
    'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue',
    sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  background-color: #f8f9fa;
  color: #212529;
  line-height: 1.6;
}

#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Navigation */
.navbar {
  background: linear-gradient(135deg, #007bff, #0056b3);
  color: white;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.nav-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
}

.nav-brand {
  color: white;
  text-decoration: none;
  transition: transform 0.2s;
}

.nav-brand:hover {
  transform: scale(1.05);
}

.brand-content {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.brand-icon {
  font-size: 2rem;
}

.brand-title {
  font-size: 1.25rem;
  font-weight: 700;
  line-height: 1;
}

.brand-subtitle {
  font-size: 0.75rem;
  opacity: 0.9;
  line-height: 1;
}

.nav-menu {
  display: flex;
  gap: 1rem;
}

.nav-link {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 500;
}

.nav-link:hover,
.nav-link.active {
  color: white;
  background-color: rgba(255, 255, 255, 0.1);
  transform: translateY(-1px);
}

.nav-icon {
  font-size: 1.1rem;
}

.mobile-menu-toggle {
  display: none;
  background: none;
  border: none;
  flex-direction: column;
  justify-content: space-around;
  width: 30px;
  height: 30px;
  cursor: pointer;
}

.mobile-menu-toggle span {
  display: block;
  height: 3px;
  width: 100%;
  background: white;
  border-radius: 3px;
  transition: all 0.3s;
}

.mobile-menu {
  display: none;
  background: rgba(0, 123, 255, 0.95);
  backdrop-filter: blur(10px);
  padding: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.mobile-nav-link {
  display: block;
  color: white;
  text-decoration: none;
  padding: 0.75rem;
  border-radius: 0.375rem;
  margin-bottom: 0.5rem;
  transition: background-color 0.2s;
}

.mobile-nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

/* Main Content */
.main-content {
  flex: 1;
  min-height: 0;
}

/* Footer */
.footer {
  background-color: #343a40;
  color: #adb5bd;
  padding: 2rem 0;
  border-top: 1px solid #495057;
  margin-top: auto;
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.footer-info p {
  margin: 0;
}

.footer-version {
  font-size: 0.8rem;
  opacity: 0.7;
}

.footer-links {
  display: flex;
  gap: 1rem;
}

.footer-link {
  color: #adb5bd;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.2s;
}

.footer-link:hover {
  color: white;
}

/* Loading */
.global-loading {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.9);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 5px solid #f3f3f3;
  border-top: 5px solid #007bff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Page Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .nav-container {
    padding: 1rem;
  }
  
  .nav-menu {
    display: none;
  }
  
  .mobile-menu-toggle {
    display: flex;
  }
  
  .mobile-menu.show {
    display: block;
  }
  
  .footer-content {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
    padding: 0 1rem;
  }
  
  .brand-title {
    font-size: 1.1rem;
  }
  
  .brand-subtitle {
    font-size: 0.7rem;
  }
}

@media (max-width: 480px) {
  .nav-container {
    padding: 0.75rem;
  }
  
  .brand-content {
    gap: 0.5rem;
  }
  
  .brand-icon {
    font-size: 1.5rem;
  }
  
  .brand-title {
    font-size: 1rem;
  }
}

/* Print Styles */
@media print {
  .navbar,
  .footer {
    display: none;
  }
  
  .main-content {
    margin: 0;
    padding: 0;
  }
  
  body {
    background: white;
  }
}
</style>