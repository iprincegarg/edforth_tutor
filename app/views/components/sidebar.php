<aside class="sidebar">
    <div class="sidebar-brand">
        <a href="<?php echo URLROOT; ?>/dashboard"><img src="<?php echo URLROOT; ?>/uploads/assets/logo.png" alt="EdForth" style="max-height: 40px; width: auto;" /></a>
    </div>
    <ul class="sidebar-nav">
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
        <!-- Users Module (Dummy) -->
        <li class="sidebar-item">
            <a href="#" class="sidebar-link">
                Students
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
                    <a href="<?php echo URLROOT; ?>/settings/front_cms" class="sidebar-link">
                        Front CMS
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="sidebar-bottom">
        <a href="<?php echo URLROOT; ?>/superadmin/logout" class="sidebar-link logout-link">
            Logout
        </a>
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