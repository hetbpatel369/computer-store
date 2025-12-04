/*!
 * Simple Dark Mode Toggler
 */

(() => {
  'use strict';

  const getStoredTheme = () => localStorage.getItem('theme');
  const setStoredTheme = theme => localStorage.setItem('theme', theme);

  const getPreferredTheme = () => {
    const storedTheme = getStoredTheme();
    if (storedTheme) {
      return storedTheme;
    }
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  };

  const setTheme = theme => {
    if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      document.documentElement.setAttribute('data-bs-theme', 'dark');
    } else {
      document.documentElement.setAttribute('data-bs-theme', theme);
    }
  };

  const updateIcons = (theme) => {
      const toggleBtn = document.querySelector('#darkModeToggle');
      if (toggleBtn) {
          const icon = toggleBtn.querySelector('i');
          // Reset classes
          icon.className = 'fas'; 
          
          if (theme === 'dark') {
              icon.classList.add('fa-sun');
          } else {
              icon.classList.add('fa-moon');
          }
      }
  };

  // Apply theme immediately
  setTheme(getPreferredTheme());

  window.addEventListener('DOMContentLoaded', () => {
    const currentTheme = getPreferredTheme();
    updateIcons(currentTheme);

    // Single Toggle Button
    const toggleBtn = document.querySelector('#darkModeToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // Determine current state
            const activeTheme = getStoredTheme() || getPreferredTheme();
            const newTheme = activeTheme === 'dark' ? 'light' : 'dark';
            
            setStoredTheme(newTheme);
            setTheme(newTheme);
            updateIcons(newTheme);
        });
    }

    // Admin Panel Buttons
    document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
      toggle.addEventListener('click', () => {
        const theme = toggle.getAttribute('data-bs-theme-value');
        setStoredTheme(theme);
        setTheme(theme);
      });
    });
  });
})();
