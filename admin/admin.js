document.addEventListener("DOMContentLoaded", () => {
    // Sub-menu Dropdown animation for User Management
    const userManagementItem = document.querySelector('a[data-target="user-management"]');
    const subMenu = document.querySelector('.sub-menu');
    
    if (userManagementItem && subMenu) {
        // Toggle the dropdown on click
        userManagementItem.addEventListener('click', (e) => {
            userManagementItem.classList.toggle('open');
            subMenu.classList.toggle('open');
        });
    }

    // SPA Navigation Logic
    const navItems = document.querySelectorAll('.nav-item');
    const contentSections = document.querySelectorAll('.content-section');

    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            
            const targetId = item.getAttribute('data-target');
            if (!targetId) return;
            
            // Remove active class from all nav items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked nav item
            item.classList.add('active');

            // Hide all content sections
            contentSections.forEach(section => {
                section.classList.remove('active');
            });

            // Show the targeted content section
            const targetSection = document.getElementById(`section-${targetId}`);
            if (targetSection) {
                targetSection.classList.add('active');
            }
        });
    });

    // Modal Logic
    const newUserBtn = document.querySelector('.new-user-btn');
    const newUserModal = document.getElementById('newUserModal');
    const closeBtns = [
        document.getElementById('closeModalBtnTop'),
        document.getElementById('closeModalBtnBottom'),
        document.getElementById('closeModalBtnSuccess')
    ];

    if (newUserBtn && newUserModal) {
        // Open Modal
        newUserBtn.addEventListener('click', () => {
            newUserModal.classList.add('active');
            window.goToStep(1); // Reset to step 1
        });

        // Close Modal
        closeBtns.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    newUserModal.classList.remove('active');
                });
            }
        });

        // Close on outside click
        newUserModal.addEventListener('click', (e) => {
            if (e.target === newUserModal) {
                newUserModal.classList.remove('active');
            }
        });
    }

    // Step switching logic
    window.goToStep = function(stepNumber) {
        const steps = document.querySelectorAll('.modal-step');
        steps.forEach(step => step.classList.remove('active'));
        
        const targetStep = document.getElementById(`step${stepNumber}`);
        if (targetStep) {
            targetStep.classList.add('active');
        }
    };
});
