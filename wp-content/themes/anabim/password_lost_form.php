<div class="password-lost-form">
    <?php if ( $attributes['show_title'] ) : ?>
        <h2><?php _e( 'BẠN QUÊN MẬT KHẨU?', SHORT_NAME ); ?></h2>
    <?php endif; ?>
    <p style="border-left: 3px solid #f08800;padding: 3px 0 3px 10px;color: #666;">
        <?php
            _e(
                "Vui lòng điền tài khoản đăng nhập và email. Bạn sẽ nhận được email có đường dẫn để tạo mật khẩu mới.",
                SHORT_NAME
            );
        ?>
    </p>
    <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <div class="form-group" style="min-width: calc(100% - 10px)">
            <label for="user_login" class="control-label"><?php _e( 'Địa chỉ E-mail', SHORT_NAME ); ?></label>
            <input type="text" name="user_login" id="user_login" class="form-control" />
        </div>
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-warning" value="<?php _e( 'Lấy mật khẩu mới', SHORT_NAME ); ?>"/>
        </div>
    </form>
</div>