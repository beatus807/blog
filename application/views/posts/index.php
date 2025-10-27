<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Post Management</h2>
    <a href="<?php echo base_url('posts/form'); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Post
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo $post->id; ?></td>
                    <td><?php echo $post->title; ?></td>
                    <td><?php echo $post->category_name; ?></td>
                    <td>
                        <span class="badge bg-<?php echo $post->status ? 'success' : 'danger'; ?>">
                            <?php echo $post->status ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($post->created_at)); ?></td>
                    <td>
                        <a href="<?php echo base_url('posts/form/' . $post->id); ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?php echo base_url('posts/toggle_status/' . $post->id); ?>" class="btn btn-sm btn-<?php echo $post->status ? 'secondary' : 'success'; ?>">
                            <i class="fas fa-<?php echo $post->status ? 'times' : 'check'; ?>"></i>
                        </a>
                        <a href="<?php echo base_url('posts/delete/' . $post->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>