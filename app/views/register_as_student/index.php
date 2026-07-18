<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME ?? 'Edforth'; ?> - Register as Student</title>
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --error-color: #ef4444;
            --input-focus: #c7d2fe;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .header-bar {
            background-color: var(--card-bg);
            box-shadow: var(--shadow-sm);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-bar .logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .container {
            max-width: 768px;
            margin: 3rem auto 5rem;
            padding: 0 1.5rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .form-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.025em;
            margin-bottom: 0.75rem;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 1.125rem;
            max-width: 32rem;
            margin: 0 auto;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            padding: 2.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--bg-color);
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .section-title::before {
            content: '';
            display: block;
            width: 4px;
            height: 24px;
            background-color: var(--primary);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 1.75rem;
        }
        
        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        .form-label span.required {
            color: var(--error-color);
            margin-left: 0.25rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            font-family: inherit;
            color: var(--text-main);
            background-color: #fff;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--input-focus);
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .radio-group, .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        
        @media (min-width: 640px) {
            .radio-group {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 1.5rem;
            }
        }

        .radio-item {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            cursor: pointer;
            color: var(--text-main);
        }
        
        .radio-item input[type="radio"] {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            background-color: var(--primary);
            color: white;
            padding: 1rem 2rem;
            font-size: 1.125rem;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3), 0 4px 6px -2px rgba(79, 70, 229, 0.15);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(1px);
            box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2);
        }
        
        .btn-submit svg {
            margin-left: 0.5rem;
            transition: transform 0.2s;
        }
        
        .btn-submit:hover svg {
            transform: translateX(4px);
        }

        .file-upload-wrapper {
            position: relative;
            width: 100%;
        }

        .file-upload-input {
            width: 100%;
            padding: 1.5rem;
            border: 2px dashed #cbd5e1;
            border-radius: 0.75rem;
            background-color: #f8fafc;
            cursor: pointer;
            text-align: center;
            color: var(--text-muted);
            transition: all 0.2s ease;
        }
        
        .file-upload-input:hover, .file-upload-input:focus {
            border-color: var(--primary);
            background-color: #f5f3ff;
            color: var(--primary);
            outline: none;
        }
        
        .file-upload-input::file-selector-button {
            margin-right: 1rem;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-upload-input::file-selector-button:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        @media (max-width: 640px) {
            .container {
                margin-top: 1.5rem;
                padding: 0 1rem;
            }
            .card {
                padding: 1.5rem;
                border-radius: 0.75rem;
            }
            .form-header h1 {
                font-size: 2rem;
            }
            .section-title {
                font-size: 1.25rem;
            }
        }

        /* Custom Multi-select styles */
        .custom-multi-select-container {
            position: relative;
            width: 100%;
        }
        .custom-multi-select-display {
            min-height: 48px;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            background-color: #fff;
            cursor: pointer;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }
        .custom-multi-select-display.open {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--input-focus);
        }
        .custom-chip {
            background-color: var(--primary);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .custom-chip-remove {
            cursor: pointer;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
        }
        .custom-chip-remove:hover {
            background: rgba(255,255,255,0.4);
        }
        .custom-placeholder {
            color: #9ca3af;
            padding-left: 0.5rem;
        }
        .custom-dropdown-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            margin-top: 0.25rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 100;
            box-shadow: var(--shadow-md);
            display: none;
        }
        .custom-dropdown-list.show {
            display: block;
        }
        .custom-dropdown-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            color: var(--text-main);
        }
        .custom-dropdown-item:hover {
            background-color: #f3f4f6;
        }
        .custom-dropdown-item.selected {
            background-color: #e0e7ff;
            color: var(--primary);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <header class="header-bar">
        <a href="<?php echo URLROOT ?? '/'; ?>" class="logo">
            <img src="<?php echo URLROOT; ?>/uploads/assets/logo.png" alt="EdForth" style="height: 32px; width: auto;" />
        </a>
    </header>

    <div class="container">
        <div class="form-header">
            <h1>Register as a Student</h1>
            <p>Learn from expert educator. Register today for a trail and start learning.</p>
            <p style="margin-top: 15px; font-weight: 500; font-size: 1rem;">Already registered? <a href="<?php echo URLROOT; ?>/student-portal" style="color: var(--primary); text-decoration: underline;">Login here</a></p>
        </div>
        
        <?php if(isset($_SESSION['form_success'])): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; border-left: 4px solid #10b981; font-weight: 500;">
                <?php 
                echo $_SESSION['form_success']; 
                unset($_SESSION['form_success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['form_error'])): ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; border-left: 4px solid #ef4444; font-weight: 500;">
                <?php 
                echo $_SESSION['form_error']; 
                unset($_SESSION['form_error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/register-as-student/submit" method="POST" enctype="multipart/form-data">
            <?php if(empty($data['sections'])): ?>
                <div class="card">
                    <p style="text-align: center; color: var(--text-muted); font-size: 1.1rem; padding: 2rem 0;">No form sections have been configured yet. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach($data['sections'] as $section): ?>
                    <?php 
                    // Get fields for this section
                    $sectionFields = array_filter($data['fields'], function($field) use ($section) {
                        return $field->section_id == $section->id;
                    });
                    
                    if(empty($sectionFields)) continue;
                    ?>
                    
                    <div class="card">
                        <h2 class="section-title"><?php echo htmlspecialchars($section->sectionName); ?></h2>
                        
                        <?php foreach($sectionFields as $field): ?>
                            <div class="form-group">
                                <label class="form-label" for="field_<?php echo $field->id; ?>">
                                    <?php echo htmlspecialchars($field->field_name); ?>
                                    <?php if($field->is_required): ?>
                                        <span class="required" title="Required">*</span>
                                    <?php endif; ?>
                                </label>
                                
                                <?php 
                                $required = $field->is_required ? 'required' : '';
                                $fieldName = 'field_' . $field->id;
                                $fieldId = 'field_' . $field->id;
                                $placeholder = htmlspecialchars($field->placeholder_text ?? '');
                                $charLimit = $field->char_limit ? 'maxlength="'.$field->char_limit.'"' : '';
                                ?>
                                
                                <?php if($field->field_type === 'text'): ?>
                                    <input type="text" id="<?php echo $fieldId; ?>" name="<?php echo $fieldName; ?>" class="form-control" placeholder="<?php echo $placeholder; ?>" <?php echo $required; ?> <?php echo $charLimit; ?>>
                                    <?php if($field->char_limit): ?>
                                        <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 6px;">Max characters: <?php echo $field->char_limit; ?></small>
                                    <?php endif; ?>
                                
                                <?php elseif($field->field_type === 'textarea'): ?>
                                    <textarea id="<?php echo $fieldId; ?>" name="<?php echo $fieldName; ?>" class="form-control" rows="4" placeholder="<?php echo $placeholder; ?>" <?php echo $required; ?> <?php echo $charLimit; ?>></textarea>
                                    <?php if($field->char_limit): ?>
                                        <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 6px;">Max characters: <?php echo $field->char_limit; ?></small>
                                    <?php endif; ?>
                                
                                <?php elseif($field->field_type === 'radio'): ?>
                                    <?php $options = array_filter(array_map('trim', explode(',', $field->field_values))); ?>
                                    <div class="radio-group">
                                        <?php foreach($options as $index => $opt): ?>
                                            <label class="radio-item" for="<?php echo $fieldId . '_' . $index; ?>">
                                                <input type="radio" id="<?php echo $fieldId . '_' . $index; ?>" name="<?php echo $fieldName; ?>" value="<?php echo htmlspecialchars($opt); ?>" <?php echo $required; ?>>
                                                <?php echo htmlspecialchars($opt); ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                <?php elseif($field->field_type === 'dropdown'): ?>
                                    <?php $options = array_filter(array_map('trim', explode(',', $field->field_values))); ?>
                                    <select id="<?php echo $fieldId; ?>" name="<?php echo $fieldName; ?>" class="form-control" <?php echo $required; ?>>
                                        <option value="" disabled selected><?php echo $placeholder ?: 'Select an option...'; ?></option>
                                        <?php foreach($options as $opt): ?>
                                            <option value="<?php echo htmlspecialchars($opt); ?>"><?php echo htmlspecialchars($opt); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                <?php elseif($field->field_type === 'multi_select'): ?>
                                    <?php $options = array_filter(array_map('trim', explode(',', $field->field_values))); ?>
                                    <select id="<?php echo $fieldId; ?>" name="<?php echo $fieldName; ?>[]" class="form-control" multiple <?php echo $required; ?>>
                                        <?php foreach($options as $opt): ?>
                                            <option value="<?php echo htmlspecialchars($opt); ?>"><?php echo htmlspecialchars($opt); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                <?php elseif($field->field_type === 'filter'): ?>
                                    <?php 
                                    $options = array_filter(array_map('trim', explode(',', $field->filterValues ?? '')));
                                    ?>
                                    <select id="<?php echo $fieldId; ?>" name="<?php echo $fieldName; ?>" class="form-control" <?php echo $required; ?>>
                                        <option value="" disabled selected><?php echo $placeholder ?: 'Choose Value...'; ?></option>
                                        <?php foreach($options as $opt): ?>
                                            <option value="<?php echo htmlspecialchars($opt); ?>"><?php echo htmlspecialchars($opt); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    
                                <?php elseif($field->field_type === 'date'): ?>
                                    <input type="date" id="<?php echo $fieldId; ?>" name="<?php echo $fieldName; ?>" class="form-control" <?php echo $required; ?>>
                                    
                                <?php elseif($field->field_type === 'file'): ?>
                                    <div class="file-upload-wrapper">
                                        <input type="file" id="<?php echo $fieldId; ?>" name="<?php echo $fieldName; ?>" class="file-upload-input" accept=".pdf,.png,.jpg,.jpeg,.zip" <?php echo $required; ?>>
                                    </div>
                                    <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 6px;">Max file size: 5MB. Supported extensions: .pdf, .png, .jpg, .jpeg, .zip</small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                
                <div style="margin-top: 2.5rem; text-align: right;">
                    <button type="submit" class="btn-submit" style="display: inline-flex; width: auto; padding: 0.6rem 1.25rem; font-size: 0.95rem; border-radius: 0.5rem;">
                        Submit Application
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-left: 0.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <?php if(isset($_SESSION['new_credentials'])): ?>
    <!-- Credentials Modal -->
    <div id="credentialsModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 9999;">
        <div style="background: white; padding: 2rem; border-radius: 8px; width: 90%; max-width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
            <h2 style="color: #0f172a; margin-bottom: 1rem;">Registration Successful!</h2>
            <p style="color: #64748b; margin-bottom: 1.5rem;">Please save your login credentials. You will need them to access your student portal.</p>
            
            <div style="background: #f1f5f9; padding: 1rem; border-radius: 6px; text-align: left; margin-bottom: 1.5rem;">
                <p style="margin: 0 0 0.5rem 0;"><strong>Username:</strong> <span style="font-family: monospace; user-select: all;"><?php echo htmlspecialchars($_SESSION['new_credentials']['username']); ?></span></p>
                <p style="margin: 0;"><strong>Password:</strong> <span style="font-family: monospace; user-select: all;"><?php echo htmlspecialchars($_SESSION['new_credentials']['password']); ?></span></p>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: center;">
                <a href="<?php echo URLROOT; ?>/student-portal" class="btn-submit" style="display: inline-block; padding: 10px 20px; border-radius: 4px; text-decoration: none; text-align: center; font-weight: bold; font-size: 0.9rem;">Go to Login</a>
                <button onclick="document.getElementById('credentialsModal').style.display='none'" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 0.9rem;">Close</button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['new_credentials']); ?>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const multiSelects = document.querySelectorAll('select[multiple]');
        
        multiSelects.forEach(select => {
            // Hide original select
            select.style.display = 'none';
            
            // Create container
            const container = document.createElement('div');
            container.className = 'custom-multi-select-container';
            select.parentNode.insertBefore(container, select);
            container.appendChild(select); // move select inside container
            
            // Create display area
            const display = document.createElement('div');
            display.className = 'custom-multi-select-display';
            container.appendChild(display);
            
            // Create dropdown list
            const dropdownList = document.createElement('div');
            dropdownList.className = 'custom-dropdown-list';
            container.appendChild(dropdownList);
            
            // Populate dropdown and initial state
            const options = Array.from(select.options);
            
            function updateDisplay() {
                display.innerHTML = '';
                let hasSelected = false;
                
                options.forEach((opt, index) => {
                    if (opt.selected) {
                        hasSelected = true;
                        const chip = document.createElement('div');
                        chip.className = 'custom-chip';
                        chip.innerHTML = `
                            ${opt.text}
                            <span class="custom-chip-remove" data-index="${index}">×</span>
                        `;
                        display.appendChild(chip);
                    }
                });
                
                if (!hasSelected) {
                    const placeholder = document.createElement('span');
                    placeholder.className = 'custom-placeholder';
                    placeholder.textContent = 'Select options...';
                    display.appendChild(placeholder);
                }
                
                // Add event listeners to remove buttons
                const removeBtns = display.querySelectorAll('.custom-chip-remove');
                removeBtns.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const idx = btn.getAttribute('data-index');
                        options[idx].selected = false;
                        renderDropdown();
                        updateDisplay();
                        // trigger change event on original select
                        select.dispatchEvent(new Event('change'));
                    });
                });
            }
            
            function renderDropdown() {
                dropdownList.innerHTML = '';
                options.forEach((opt, index) => {
                    const item = document.createElement('div');
                    item.className = 'custom-dropdown-item' + (opt.selected ? ' selected' : '');
                    item.textContent = opt.text;
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        opt.selected = !opt.selected; // toggle
                        renderDropdown();
                        updateDisplay();
                        select.dispatchEvent(new Event('change'));
                    });
                    dropdownList.appendChild(item);
                });
            }
            
            display.addEventListener('click', () => {
                const isOpen = dropdownList.classList.contains('show');
                // Close all other dropdowns
                document.querySelectorAll('.custom-dropdown-list').forEach(list => list.classList.remove('show'));
                document.querySelectorAll('.custom-multi-select-display').forEach(disp => disp.classList.remove('open'));
                
                if (!isOpen) {
                    dropdownList.classList.add('show');
                    display.classList.add('open');
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!container.contains(e.target)) {
                    dropdownList.classList.remove('show');
                    display.classList.remove('open');
                }
            });
            
            updateDisplay();
            renderDropdown();
        });
    });
    </script>
</body>
</html>
