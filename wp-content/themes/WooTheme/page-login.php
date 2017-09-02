<?php get_header(); ?>

	<!-- Start content by page -->

	<div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h2>Войти</h2>
                <form action="#" class="woo_login_form">
                    <div class="error_flash alert alert-danger"></div>
                    <div class="form-group login">
                        <input class="form-control" name="log" type="text" placeholder="Введите ваш логин или email">
                    </div>
                    <div class="form-group password">
                        <input class="form-control" name="pwd" type="password" placeholder="Введите ваш пароль">
                    </div>
                    <div class="form-group">
                        <input name="rememberme" type="checkbox"> Запомнить меня
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Войти</button>
                    </div>
                </form>
            </div>
        </div>
	</div>

<?php get_footer();?>