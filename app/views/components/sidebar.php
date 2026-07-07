<aside class="sidebar">
    <div class="sidebar-brand">
        <h2>EdForth</h2>
    </div>
    <ul class="sidebar-nav">
        <!-- Dashboard Module -->
        <li class="sidebar-item">
            <a href="<?php echo URLROOT; ?>/dashboard" class="sidebar-link active">
                Overview
            </a>
        </li>
        <!-- Tutors Module (Dummy) -->
        <li class="sidebar-item">
            <a href="#" class="sidebar-link">
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