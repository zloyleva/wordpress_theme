(function($){
    $(function(){

        var errorFlashSection = $('.error_flash');

        $(document).ready(function () {
            cleanErrors();
        });

        $(document).on('submit', '.woo_login_form', function(event){
            event.preventDefault();
            console.log('Login');

            var login = $('input[name="log"]').val();
            var pwd = $('input[name="pwd"]').val();

            if(login.length < 1 && pwd.length < 1){
                errorDisplay('password','Заполните поля!');
                return;
            }

            var data = {
                log: login,
                pwd: $('input[name="pwd"]').val(),
                rememberme: $('input[name="rememberme"]').prop( "checked" ),
                action: 'woo_login_user'
            }

            console.log(get_data.url);
            $.ajax({
                url: get_data.url,
                method: 'post',
                data: data,
                dataType: 'json',
                success: function(responce){
                    if(typeof responce.errors && responce.errors){
                        cleanErrors();

                        console.log('error!');
                        if(typeof responce.errors.empty_password !== 'undefined' && responce.errors.empty_password){
                            errorDisplay('password','Введите пароль');
                        }
                        if(typeof responce.errors.incorrect_password !== 'undefined' && responce.errors.incorrect_password){
                            errorDisplay('password', 'Неверный пароль. Введите правильный пароль');
                        }
                        if(typeof responce.errors.empty_username !== 'undefined' && responce.errors.empty_username){
                            errorDisplay('login', 'Введите Ваш логин');
                        }
                        if(typeof responce.errors.invalid_username !== 'undefined' && responce.errors.invalid_username){
                            errorDisplay('login', 'Вы ввели неверный или несуществующий логин');
                        }
                    }
                    if(typeof responce.data){
                        console.log(responce);
                        window.location.href = responce;
                    }

                }
            });
        });

        function errorDisplay(section, errorMsg){
            $(errorFlashSection).show();
            $('.form-group.' + section).addClass('has-error');
            $(errorFlashSection).text(errorMsg);
        }

        // Clear error alert section
        function cleanErrors(){
            $(errorFlashSection).text('');
            $(errorFlashSection).hide();
        }

    });
})(jQuery)