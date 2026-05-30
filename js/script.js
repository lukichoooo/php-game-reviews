$(function() {

    // ---- Register form validation ----
    $('#register-form').on('submit', function(e) {
        var valid = true;
        var email = $('#email').val().trim();
        var pass = $('#password').val();
        var confirm = $('#confirm').val();

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#email-error').text('Valid email required').fadeIn();
            valid = false;
        } else {
            $('#email-error').fadeOut();
        }

        if (pass.length < 4) {
            $('#pass-error').text('Password must be at least 4 characters').fadeIn();
            valid = false;
        } else {
            $('#pass-error').fadeOut();
        }

        if (pass !== confirm) {
            $('#confirm-error').text('Passwords do not match').fadeIn();
            valid = false;
        } else {
            $('#confirm-error').fadeOut();
        }

        if (!valid) e.preventDefault();
    });

    // ---- Category filter on reviews page ----
    $('.cat-filters .btn').on('click', function() {
        $('.cat-filters .btn').removeClass('active');
        $(this).addClass('active');
        var cat = $(this).data('cat');

        $('.review-grid .card').each(function() {
            if (cat === 'all' || $(this).data('cat') == cat) {
                $(this).fadeIn(300);
            } else {
                $(this).fadeOut(300);
            }
        });
    });

    // ---- Color picker live preview ----
    $('#accent-color').on('input', function() {
        var hex = $(this).val();
        var r = parseInt(hex.slice(1,3), 16);
        var g = parseInt(hex.slice(3,5), 16);
        var b = parseInt(hex.slice(5,7), 16);
        var hsl = rgbToHsl(r, g, b);
        $('#hex-val').text(hex);
        $('#rgba-val').text('rgba(' + r + ',' + g + ',' + b + ',1)');
        $('#hsl-val').text('hsl(' + Math.round(hsl[0]) + ',' + Math.round(hsl[1]) + '%,' + Math.round(hsl[2]) + '%)');
    });

    function rgbToHsl(r, g, b) {
        r /= 255; g /= 255; b /= 255;
        var max = Math.max(r, g, b), min = Math.min(r, g, b);
        var h, s, l = (max + min) / 2;
        if (max === min) { h = s = 0; }
        else {
            var d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r: h = ((g - b) / d + (g < b ? 6 : 0)) / 6; break;
                case g: h = ((b - r) / d + 2) / 6; break;
                case b: h = ((r - g) / d + 4) / 6; break;
            }
        }
        return [h * 360, s * 100, l * 100];
    }

});
