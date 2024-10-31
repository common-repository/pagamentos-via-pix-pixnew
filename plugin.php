<?php
/**
 * @link https://wordpress.org/plugins/pagamentos-via-pix-pixnew/
 * @wordpress-plugin
 * Plugin Name: Pagamentos via Pix | PixNew 
 * Plugin URI: https://pixnew.com.br
 * Author Name: PixNew
 * Author URI: https://pixnew.com.br
 * Description: Receba pagamentos via PIX no WooCommerce com a PixNew de forma automática sem precisar de comprovantes de pagamento.
 * Requires at least:           5.2
 * Requires PHP:                7.0
 * WC requires at least:        3.0
 * WC tested up to:             6.1
 * License:                     GPLv2 or later
 * Language:                    pt_BR
 * Version:                     1.1.1
 * Stable Tag:                  1.1.1
 * Requires WooCommerce at least: 2.1
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: pixnew-pay-woo
*/ 

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

add_action( 'plugins_loaded', 'pixnew_payment_init', 11 );

function pixnew_payment_init() {
    
    if( class_exists( 'WC_Payment_Gateway' ) ) {
        
        class WC_PixNew_pay_Gateway extends WC_Payment_Gateway {
            
            public function __construct() {

                $this->id   = 'pixnew_payment';
                $this->icon = apply_filters( 'woocommerce_pixnew_icon', plugins_url('/pix-logo.png', __FILE__ ) );
                $this->has_fields = false;
                $this->method_title = __( 'Pagamento PixNew', 'pixnew-pay-woo');
                $this->method_description = __( 'Pagamentos Pix com status do pedido alterado automaticamente e recursos avançados.', 'pixnew-pay-woo');

                $this->title = $this->get_option( 'title' );
                $this->description = $this->get_option( 'description' );
                
                $payment_link = !preg_match( "~^(?:f|ht)tps?://~i", $this->get_option('payment_link') ) ? "https://" . $this->get_option('payment_link') : $this->get_option('payment_link');
                $this->payment_link = $payment_link;

                $this->init_form_fields();
                $this->init_settings();

                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
                add_action( 'woocommerce_api_pixnew', array( $this, 'webhook' ) );
                
                add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

                if (is_account_page()) {
                    add_action('woocommerce_order_details_after_order_table', array($this, 'order_page'));
                }

                $basename = plugin_basename( __FILE__ );
                add_filter( 
                    "plugin_action_links_$basename", 
                    array( $this,'plugin_action_links' )
                );
            }

            public static function plugin_action_links( $links ) {
                $plugin_links   = array();
                $plugin_links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=pixnew_payment' ) ) . '">Configurações</a>';

                return array_merge( $plugin_links, $links );
            }

            function showIframe( $order_id ) {
                $order = new WC_Order( $order_id );

                $payment_link = $order->get_meta('payment_link_pix_new');
                
                if($payment_link) {
                    ?>
                    <div class="wcpix-container" style="text-align: center;margin: 20px 0">
                        <div id="payment_link_pix_new" style="margin:0px;padding:0px;overflow:hidden;width: 100%;position: relative;text-align: center;">
                            <iframe src="<?php echo $payment_link ?>" frameborder="0" style="overflow:hidden;height:640px;width:100%" height="640px" width="100%"></iframe>
                        </div>
                        <div class="wcpix-instructions">
                            Pagamento processado por PixNew
                        </div>
                    </div>
                    <?php
                }
            }

            function thankyou_page( $order_id ) {
                //$order = wc_get_order( $order_id );
                //$order->add_order_note( 'Pedido registrado na PixNew. Transação: xxx ' );
                return $this->showIframe($order_id);
            }

            public function order_page($order)
            {
                $order_id = $order->get_id();
                return $this->showIframe($order_id);
            }

            public function init_form_fields() {
                $this->form_fields = apply_filters( 'woo_pixnew_pay_fields', array(
                    'enabled' => array(
                        'title' => __( 'Habilitar', 'pixnew-pay-woo'),
                        'type' => 'checkbox',
                        'label' => __( 'Marque para Habilitar o Plugin', 'pixnew-pay-woo'),
                        'default' => 'no'
                    ),
                    'title' => array(
                        'title' => __( 'Título', 'pixnew-pay-woo'),
                        'type' => 'text',
                        'default' => __( 'Pagamento por PIX', 'pixnew-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'O título da forma de pagamento que o cliente vê durante o checkout.', 'pixnew-pay-woo')
                    ),
                    'description' => array(
                        'title' => __( 'Descrição', 'pixnew-pay-woo'),
                        'type' => 'textarea',
                        'default' => __( 'Você será redirecionado para realizar o pagamento do QRCODE.', 'pixnew-pay-woo'),
                        'desc_tip' => true,
                        'description' => __( 'Descrição da forma de pagamento que o cliente verá no seu checkout.', 'pixnew-pay-woo')
                    ),
                    'payment_link'    => array(
                        'title'       => __('Link de pagamento', 'pixnew-pay-woo'),
                        'type'        => 'text',
                        'description' => __('Informe seu link de pagamento. Ele é necessário para poder processar os pagamentos. <br> <a href="https://pixnew.com.br" target="_blank">Acesse a PixNew</a>, crie sua conta grátis e copie o seu link.', 'pixnew-pay-woo'),
                        'default'     => '',
                        'required'	  => true,
                    ),
                    'enabled_payment_on_order_success' => array(
                        'title' => __( 'Pix Transparente', 'pixnew-pay-woo'),
                        'type' => 'checkbox',
                        'label' => __( 'Ativar', 'pixnew-pay-woo'),
                        'description' => __('Ativando essa opção, o QRCODE para pagamento PIX será gerado na finalização da compra, sem a necessidade do cliente sair da sua loja/site. <br> Deixando desabilitado, o cliente será redirecionado para o link de pagamento e assim que o pagamento for identificado ele será redirecionado para a página de finalização da compra na sua loja/site. <br /><br /><br /> Para suporte, dúvidas ou sugestões, fale conosco por <a href="https://wa.me/5551998892698?text=Ol%C3%A1%20PixNew,%20me%20chamo%20" target="_blank">WhatsApp</a> ou <a href="" target="_blank">hello@pixnew.com.br</a>', 'pixnew-pay-woo'),
                        'default' => 'no'
                    ),
                ));
            }

            public function process_payment( $order_id ) {
                $order = wc_get_order( $order_id );

                $previous_order_status = $order->get_status(); // pending || cancelled || on-hold || failed || processing

                $order->update_status( 'wc-pending',  __( 'Aguardando Pagamento do PIX', 'pixnew-pay-woo') );

                WC()->cart->empty_cart();

                $order_amount = $order->get_total();
                
                $customer_email = $order->get_billing_email();
                
                $site_host = parse_url( get_site_url(), PHP_URL_HOST );
                $webhook = 'https://' . $site_host . '/wc-api/pixnew?order_id=' . $order_id;
                $pix_webhook_url = rawurlencode(html_entity_decode($webhook));
                
                $redirect = $order->get_checkout_order_received_url();
                $pix_redirect_url = rawurlencode(html_entity_decode($redirect));

                $payment_link = !preg_match( "~^(?:f|ht)tps?://~i", $this->get_option('payment_link') ) ? "https://" . $this->get_option('payment_link') : $this->get_option('payment_link');

                $payment_link_redirect = $payment_link . '?pix_amount=' . $order_amount . '&payer_email=' . $customer_email . '&pix_webhook_url=' . $pix_webhook_url . '&pix_redirect_url=' . $pix_redirect_url;

                if( $this->get_option('enabled_payment_on_order_success') == 'yes' ) {
                    $payment_link_pix_new = $order->get_meta('payment_link_pix_new');

                    if(!$payment_link_pix_new || $previous_order_status == 'failed') {
                        $payment_link_redirect = $payment_link_redirect . '&embed=1';
                        $payment_link_request = wp_remote_get( $payment_link_redirect, array('sslverify' => FALSE) );
                    
                        if( is_wp_error( $payment_link_request ) ) {
                            //error_log( print_r($payment_link_request, TRUE) );die();        
                            //return false; 
                        }
    
                        $body = wp_remote_retrieve_body( $payment_link_request );
                        $payment_link = json_decode( $body )->payment_link;
        
                        // WooCommerce 3.0 or later
                        if (!method_exists($order, 'update_meta_data')) {
                            update_post_meta($order_id, 'payment_link_pix_new', $payment_link);
                        } else {
                            $order->update_meta_data('payment_link_pix_new', $payment_link);
                            $order->save();
                        }
                    } 
                    
                    $payment_link_redirect = $this->get_return_url( $order );
                }

				//$order->add_order_note( '<a href="' . $result->links->linkQrCode . '" target="_blank">Ver QR Code</a><br />Pix Copia e cola: <code>' . $result->links->emv . '</code>' );
				//$this->log( 'Gerando cobrança para o pedido #' . $order->get_id() . ': ' . print_r( $data, true ) );

                return array(
                    'result'   => 'success',
                    'redirect' => $payment_link_redirect,
                );
            }

            public function webhook() {
                header( 'HTTP/1.1 200 OK' );
                header( 'Content-Type: application/json' );

                $data = file_get_contents('php://input');
                parse_str($data, $pix_payment);

                $order_id = $_GET['order_id'];
                $order = wc_get_order( $order_id );

                $order_status = $order->get_status(); // pending || cancelled || on-hold || failed || processing || paid

                if( $order_status != 'paid' && $order_status != 'processing' && $order_status != 'cancelled' ) {
                    if($pix_payment['status'] == 'paid' && $order_status == 'pending') {
                        $order->payment_complete();
                        
                        if (function_exists('wc_reduce_stock_levels')) {
                            wc_reduce_stock_levels($order_id);
                        }
                    }  

                    if( $pix_payment['status'] == 'expired' && $order_status == 'pending' ) {
                        $order->update_status( 'wc-failed',  __( 'Pagamento do PIX expirado', 'pixnew-pay-woo') );
                    }
                }

                update_option('webhook_debug', $_GET);
            }
        }
    }
}

add_filter( 'woocommerce_payment_gateways', 'add_to_woo_pixnew_payment_gateway');

function add_to_woo_pixnew_payment_gateway( $gateways ) {
    $gateways[] = 'WC_PixNew_pay_Gateway';
    return $gateways;
}
