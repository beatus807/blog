<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Category Management</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus"></i> Add Category
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table id="categoriesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo $category->id; ?></td>
                    <td><?php echo htmlspecialchars($category->name); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $category->status ? 'success' : 'danger'; ?>">
                            <?php echo $category->status ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($category->created_at)); ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-category" data-id="<?php echo $category->id; ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-<?php echo $category->status ? 'secondary' : 'success'; ?> toggle-status" 
                                data-id="<?php echo $category->id; ?>" 
                                data-status="<?php echo $category->status; ?>">
                            <i class="fas fa-<?php echo $category->status ? 'times' : 'check'; ?>"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-category" data-id="<?php echo $category->id; ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCategoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    console.log('jQuery is working!');
    
    // Initialize DataTables
    $('#categoriesTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [10, 25, 50, 100]
    });

    // Add Category Form
    $('#addCategoryForm').on('submit', function(e) {
        e.preventDefault();
        
        var name = $('#name').val().trim();
        if (name.length < 2) {
            alert('Category name must be at least 2 characters long');
            return;
        }
        
        $.ajax({
            url: '<?php echo site_url('categories/create'); ?>',
            type: 'POST',
            data: { name: name },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#addCategoryModal').modal('hide');
                    $('#addCategoryForm')[0].reset();
                    alert('Category added successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('An error occurred. Please check the console for details.');
            }
        });
    });

    // Edit Category - Open Modal
    $(document).on('click', '.edit-category', function() {
        var id = $(this).data('id');
        var currentName = $(this).closest('tr').find('td:eq(1)').text().trim();
        
        $('#edit_id').val(id);
        $('#edit_name').val(currentName);
        $('#editCategoryModal').modal('show');
    });

    // Update Category Form
    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#edit_id').val();
        var name = $('#edit_name').val().trim();
        
        if (name.length < 2) {
            alert('Category name must be at least 2 characters long');
            return;
        }
        
        $.ajax({
            url: '<?php echo site_url('categories/edit/'); ?>' + id,
            type: 'POST',
            data: { name: name },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#editCategoryModal').modal('hide');
                    alert('Category updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('An error occurred. Please check the console for details.');
            }
        });
    });

    // Toggle Status
    $(document).on('click', '.toggle-status', function() {
        var id = $(this).data('id');
        var currentStatus = $(this).data('status');
        var action = currentStatus ? 'deactivate' : 'activate';
        
        if (confirm('Are you sure you want to ' + action + ' this category?')) {
            $.ajax({
                url: '<?php echo site_url('categories/toggle_status/'); ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Status updated successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('An error occurred. Please check the console for details.');
                }
            });
        }
    });

    // Delete Category
    $(document).on('click', '.delete-category', function() {
        var id = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            $.ajax({
                url: '<?php echo site_url('categories/delete/'); ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Category deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('An error occurred. Please check the console for details.');
                }
            });
        }
    });
});
</script>