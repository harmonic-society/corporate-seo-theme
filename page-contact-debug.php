<?php
/**
 * Template Name: Contact Debug
 * 
 * デバッグ用のコンタクトページテンプレート
 * 
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <h1>Contact Form Debug</h1>
        
        <div style="background: #f0f0f0; padding: 20px; margin: 20px 0;">
            <h2>Debug Information</h2>
            <p><strong>Ajax URL:</strong> <?php echo admin_url( 'admin-ajax.php' ); ?></p>
            <p><strong>Nonce:</strong> <?php echo wp_create_nonce( 'contact_form_submit' ); ?></p>
            <p><strong>Theme URL:</strong> <?php echo get_template_directory_uri(); ?></p>
            <p><strong>WordPress Version:</strong> <?php echo get_bloginfo( 'version' ); ?></p>
            <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        </div>
        
        <h2>Simple Test Form</h2>
        <form id="debug-contact-form" method="post">
            <?php wp_nonce_field( 'contact_form_submit', 'contact_nonce' ); ?>
            <input type="hidden" name="action" value="submit_contact_form">
            
            <p>
                <label>Name: <input type="text" name="your_name" required></label>
            </p>
            <p>
                <label>Email: <input type="email" name="your_email" required></label>
            </p>
            <p>
                <label>Type: 
                    <select name="inquiry_type" required>
                        <option value="">Select</option>
                        <option value="Service">Service</option>
                        <option value="Other">Other</option>
                    </select>
                </label>
            </p>
            <p>
                <label>Message: <textarea name="your_message" required></textarea></label>
            </p>
            <p>
                <label><input type="checkbox" name="privacy_policy" required> Agree to privacy policy</label>
            </p>
            <p>
                <button type="submit">Submit</button>
            </p>
        </form>
        
        <div id="debug-results" style="margin-top: 20px; padding: 20px; background: #fff; border: 1px solid #ddd; display: none;">
            <h3>Results:</h3>
            <pre id="debug-output"></pre>
        </div>
        
        <h2>Custom Form</h2>
        <?php echo do_shortcode( '[custom_contact_form]' ); ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('debug-contact-form');
    const results = document.getElementById('debug-results');
    const output = document.getElementById('debug-output');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            results.style.display = 'block';
            output.textContent = 'Submitting...';
            
            const formData = new FormData(form);
            const ajaxUrl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
            
            // Log FormData contents
            console.log('FormData contents:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            fetch(ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response headers:', response.headers);
                return response.text(); // まずテキストとして取得
            })
            .then(text => {
                console.log('Raw response:', text);
                output.textContent = 'Raw Response:\n' + text;
                
                try {
                    const data = JSON.parse(text);
                    output.textContent += '\n\nParsed JSON:\n' + JSON.stringify(data, null, 2);
                } catch (e) {
                    output.textContent += '\n\nJSON Parse Error: ' + e.message;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                output.textContent = 'Fetch Error:\n' + error.toString();
            });
        });
    }
});
</script>

<?php get_footer(); ?>