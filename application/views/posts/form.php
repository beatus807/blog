<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0"><?php echo isset($post) ? 'Edit Post' : 'Add New Post'; ?></h4>
    </div>
    <div class="card-body">
        <?php echo form_open_multipart('posts/form/' . (isset($post) ? $post->id : '')); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->id; ?>" 
                                <?php echo (isset($post) && $post->category_id == $category->id) ? 'selected' : ''; ?>>
                                <?php echo $category->name; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1" <?php echo (isset($post) && $post->status) ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo (isset($post) && !$post->status) ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?php echo isset($post) ? $post->title : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="cover_image" class="form-label">Cover Image</label>
                <input type="file" class="form-control" id="cover_image" name="cover_image">
                <?php if (isset($post) && $post->cover_image): ?>
                <div class="mt-2">
                    <img src="<?php echo base_url('uploads/' . $post->cover_image); ?>" alt="Cover Image" style="max-width: 200px;">
                </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="10" required><?php echo isset($post) ? $post->description : ''; ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><?php echo isset($post) ? 'Update' : 'Create'; ?> Post</button>
            <a href="<?php echo base_url('posts'); ?>" class="btn btn-secondary">Cancel</a>
        <?php echo form_close(); ?>
    </div>
</div>