<aside class="sidebar">
    <div class="sidebar-brand">
        <a href="<?php echo URLROOT; ?>/dashboard"><img src="<?php echo URLROOT; ?>/uploads/assets/logo.png" alt="EdForth" style="max-height: 40px; width: auto;" /></a>
    </div>
    <ul class="sidebar-nav">
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'tutor'): ?>
            <!-- Tutor Navigation -->
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/tutor-dashboard" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/tutor-dashboard') !== false && strpos($_SERVER['REQUEST_URI'], '/tickets') === false && strpos($_SERVER['REQUEST_URI'], '/myClasses') === false ? 'active' : ''; ?>">
                    My Profile
                </a>
            </li>
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/tutor-dashboard/tickets" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/tutor-dashboard/tickets') !== false ? 'active' : ''; ?>">
                    My Tickets
                </a>
            </li>
            <!-- Classes Module -->
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/tutor-dashboard/myClasses" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/tutor-dashboard/myClasses') !== false ? 'active' : ''; ?>">
                    My Classes
                </a>
            </li>
            </li>
        <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'student'): ?>
            <!-- Student Navigation -->
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/student-dashboard" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/student-dashboard') !== false && strpos($_SERVER['REQUEST_URI'], '/tickets') === false && strpos($_SERVER['REQUEST_URI'], '/myClasses') === false && strpos($_SERVER['REQUEST_URI'], '/bookTutor') === false ? 'active' : ''; ?>">
                    My Profile
                </a>
            </li>
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/student-dashboard/bookTutor" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/student-dashboard/bookTutor') !== false ? 'active' : ''; ?>">
                    Book Tutor
                </a>
            </li>
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/student-dashboard/myClasses" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/student-dashboard/myClasses') !== false ? 'active' : ''; ?>">
                    My Classes
                </a>
            </li>
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/student-dashboard/tickets" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/student-dashboard/tickets') !== false ? 'active' : ''; ?>">
                    My Tickets
                </a>
            </li>
        <?php else: ?>
            <!-- Admin Navigation -->
            <!-- Dashboard Module -->
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/dashboard" class="sidebar-link active">
                    Overview
                </a>
            </li>
            <!-- Tutors Module -->
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/tutors" class="sidebar-link">
                    Tutors
                </a>
            </li>
            <!-- Students Module -->
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/students" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/students') !== false ? 'active' : ''; ?>">
                    Students
                </a>
            </li>
            <!-- Support Tickets Module -->
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/superadmin/tickets" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/superadmin/tickets') !== false ? 'active' : ''; ?>">
                    Support Tickets
                </a>
            </li>
            <!-- Classes Module -->
            <li class="sidebar-item">
                <a href="<?php echo URLROOT; ?>/superadmin/classes" class="sidebar-link <?php echo strpos($_SERVER['REQUEST_URI'], '/superadmin/classes') !== false ? 'active' : ''; ?>">
                    Classes
                </a>
            </li>
            <!-- Settings Module -->
            <li class="sidebar-item">
                <a class="sidebar-link dropdown-toggle" onclick="toggleDropdown(this)">
                    Settings
                </a>
                <ul class="sidebar-dropdown">
                    <li>
                        <a href="<?php echo URLROOT; ?>/settings/tutor_form" class="sidebar-link">
                            Tutor Form
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/settings/student_form" class="sidebar-link">
                            Student Form
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/settings/front_cms" class="sidebar-link">
                            Front CMS
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif; ?>
    </ul>

    <div class="sidebar-bottom">
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'tutor'): ?>
            <a href="<?php echo URLROOT; ?>/tutor-portal/logout" class="sidebar-link logout-link">
                Logout
            </a>
        <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'student'): ?>
            <a href="<?php echo URLROOT; ?>/student-portal/logout" class="sidebar-link logout-link">
                Logout
            </a>
        <?php else: ?>
            <a href="<?php echo URLROOT; ?>/superadmin/logout" class="sidebar-link logout-link">
                Logout
            </a>
        <?php endif; ?>
    </div>
</aside>

<script>
    function toggleDropdown(element) {
        element.classList.toggle('open');
        var dropdown = element.nextElementSibling;
        if (dropdown && dropdown.classList.contains('sidebar-dropdown')) {
            dropdown.classList.toggle('show');
        }
    }
</script>