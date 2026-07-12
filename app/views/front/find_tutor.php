<?php require_once APPROOT . '/views/inc/front_header.php'; ?>

<!-- Top banner / Breadcrumb -->
<div class="home"
    style="background: #f8f9fa; display: flex; align-items: center; justify-content: center; text-align: center; border-bottom: 1px solid #e2e8f0; padding: 40px 20px;">
    <div class="home_content">
        <h1 style="font-size: 2.25rem; color: #0f172a; font-weight: 700; margin-top: 0; padding-top: 0;">Find a Tutor
        </h1>
        <p style="color: #64748b; font-size: 1.05rem; margin-top: 8px; margin-bottom: 0;">Browse our expert tutors and
            find the perfect match for your needs.</p>
    </div>
</div>

<div class="container" style="padding: 60px 0;">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div
                style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h4
                    style="margin-bottom: 20px; color: #0f172a; font-weight: 600; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                    Filters</h4>
                <form action="<?php echo URLROOT; ?>/find-tutor" method="GET">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label
                            style="font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Keywords..."
                            value="<?php echo htmlspecialchars($data['search']); ?>"
                            style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px;">
                    </div>

                    <?php foreach ($data['filterFields'] as $field): ?>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label
                                style="font-weight: 600; color: #475569; margin-bottom: 8px; display: block;"><?php echo htmlspecialchars($field->field_name); ?></label>
                            <select name="filter_field_<?php echo $field->id; ?>" class="form-control"
                                style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px;">
                                <option value="">All</option>
                                <?php
                                $values = explode(',', $field->filterValues);
                                $selectedValue = isset($data['activeFilters'][$field->id]) ? $data['activeFilters'][$field->id] : '';
                                foreach ($values as $val):
                                    $val = trim($val);
                                    ?>
                                    <option value="<?php echo htmlspecialchars($val); ?>" <?php echo ($selectedValue === $val) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($val); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endforeach; ?>

                    <button type="submit" class="btn"
                        style="width: 100%; background: #3b82f6; color: white; padding: 10px; font-weight: 600; border-radius: 4px; border: none; margin-top: 10px;">Apply
                        Filters</button>
                    <a href="<?php echo URLROOT; ?>/find-tutor" class="btn"
                        style="width: 100%; background: #f1f5f9; color: #475569; padding: 10px; font-weight: 600; border-radius: 4px; border: none; margin-top: 10px; text-align: center; text-decoration: none; display: block;">Clear
                        Filters</a>
                </form>
            </div>
        </div>

        <!-- Tutors Grid -->
        <div class="col-lg-9">
            <?php if (empty($data['tutors'])): ?>
                <div
                    style="background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fa fa-search" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 20px;"></i>
                    <h3 style="color: #0f172a;">No Tutors Found</h3>
                    <p style="color: #64748b;">Try adjusting your filters to find more tutors.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($data['tutors'] as $tutor):
                        $formData = json_decode($tutor->form_data, true);
                        if (!$formData)
                            continue;
                        ?>
                        <div class="col-md-4 col-lg-4 mb-4">
                            <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: transform 0.2s; border: 1px solid #f1f5f9;"
                                onmouseover="this.style.transform='translateY(-5px)'"
                                onmouseout="this.style.transform='translateY(0)'">

                                <?php
                                $fullName = 'Tutor #' . $tutor->id;
                                $profilePic = '';
                                $usedFields = [];

                                foreach ($data['publicFields'] as $field) {
                                    $fieldKey = 'field_' . $field->id;
                                    if (isset($formData[$fieldKey]) && !empty($formData[$fieldKey])) {
                                        $fieldNameLower = strtolower($field->field_name);

                                        // Find name
                                        if (strpos($fieldNameLower, 'name') !== false && !in_array('name', $usedFields)) {
                                            $fullName = is_array($formData[$fieldKey]) ? implode(', ', $formData[$fieldKey]) : $formData[$fieldKey];
                                            $usedFields[] = 'name';
                                            $usedFields[] = $field->id;
                                        }

                                        // Find profile photo
                                        if ($field->field_type === 'file' && (strpos($fieldNameLower, 'photo') !== false || strpos($fieldNameLower, 'profile') !== false || strpos($fieldNameLower, 'image') !== false)) {
                                            $profilePic = $formData[$fieldKey];
                                            $usedFields[] = $field->id;
                                        }
                                    }
                                }
                                ?>

                                <div
                                    style="padding: 15px; border-bottom: 1px solid #f1f5f9; background: linear-gradient(to right, #f8fafc, #ffffff);">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div
                                            style="width: 48px; height: 48px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: bold; overflow: hidden; flex-shrink: 0;">
                                            <?php if ($profilePic): ?>
                                                <img src="<?php echo URLROOT . '/' . htmlspecialchars($profilePic); ?>"
                                                    alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <i class="fa fa-user"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div style="min-width: 0; flex: 1;">
                                            <h4 style="margin: 0; color: #0f172a; font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                title="<?php echo htmlspecialchars($fullName); ?>">
                                                <?php echo htmlspecialchars($fullName); ?>
                                            </h4>
                                            <span
                                                style="background: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 10px; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; margin-top: 3px; display: inline-block;">Verified</span>
                                        </div>
                                    </div>
                                </div>

                                <div style="padding: 15px; flex-grow: 1;">
                                    <?php foreach ($data['publicFields'] as $field):
                                        if (in_array($field->id, $usedFields))
                                            continue;

                                        $fieldKey = 'field_' . $field->id;
                                        if (!isset($formData[$fieldKey]) || empty($formData[$fieldKey]))
                                            continue;

                                        $value = $formData[$fieldKey];
                                        if (is_array($value)) {
                                            $value = implode(', ', $value);
                                        }
                                        ?>
                                        <div style="margin-bottom: 12px;">
                                            <strong
                                                style="color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 2px;"><?php echo htmlspecialchars($field->field_name); ?></strong>
                                            <div style="color: #1e293b; font-weight: 500; font-size: 0.95rem;">
                                                <?php if ($field->field_type === 'file'): ?>
                                                    <img src="<?php echo URLROOT . '/' . htmlspecialchars($value); ?>"
                                                        alt="<?php echo htmlspecialchars($field->field_name); ?>"
                                                        style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;">
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars(strlen($value) > 100 ? substr($value, 0, 100) . '...' : $value); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div
                                    style="padding: 15px; background: #f8fafc; border-top: 1px solid #f1f5f9; text-align: center;">
                                    <a href="<?php echo URLROOT; ?>/student-portal" class="btn"
                                        style="display: block; background: #f59e0b; color: white; border: none; padding: 8px 24px; font-weight: 600; border-radius: 4px; width: 100%; text-decoration: none;">Book
                                        Trail Class</a>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($data['totalPages'] > 1): ?>
                    <div style="margin-top: 40px; display: flex; justify-content: center; gap: 10px;">
                        <?php for ($i = 1; $i <= $data['totalPages']; $i++):
                            $isActive = ($i == $data['page']);
                            $query = $_GET;
                            $query['page'] = $i;
                            $queryString = http_build_query($query);
                            ?>
                            <a href="<?php echo URLROOT; ?>/find-tutor?<?php echo $queryString; ?>"
                                style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 4px; font-weight: 600; text-decoration: none; <?php echo $isActive ? 'background: #3b82f6; color: white;' : 'background: #fff; color: #475569; border: 1px solid #cbd5e1;'; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/inc/front_footer.php'; ?>