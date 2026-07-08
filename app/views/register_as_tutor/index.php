<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME ?? 'Edforth'; ?> - Register as Tutor</title>
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
    </style>
</head>
<body>
    <header class="header-bar">
        <a href="<?php echo URLROOT ?? '/'; ?>" class="logo">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
            </svg>
            <?php echo SITENAME ?? 'Edforth'; ?>
        </a>
    </header>

    <div class="container">
        <div class="form-header">
            <h1>Register as a Tutor</h1>
            <p>Join our community of expert educators. Share your knowledge and make an impact today.</p>
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

        <form action="<?php echo URLROOT; ?>/register-as-tutor/submit" method="POST" enctype="multipart/form-data">
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
</body>
</html>
