=== Pix automático e transparente - PixNew + WooCommerce ===
Contributors: PixNew
Donate link: www.pixnew.com.br
Tags: pix, pixnew, woocommerce, payment, payment gateway, e-commerce, pagamento, meio de pagamento, boleto, pix, shop, cart, checkout, paypal, stripe, woo commerce, pagseguro, PicPay, Nubank
Requires at least: 3.3
Tested up to: 6.1
Requires PHP: 7.0
Version: 1.1.1
Stable Tag: 1.1.1
License: GPLv3
Language: pt_BR
License URI: https://www.gnu.org/licenses/gpl-3.0.html

=== PAGAMENTOS PIX COM ATUALIZAÇÃO AUTOMÁTICA DE STATUS E COM PIX TRANSPARENTE, PAGAMENTO SEM SAIR DA LOJA. ===

=== DIFERENCIAIS ===

🔥 Use as chaves e o banco que você já possui sem necessidade de cadastrar uma nova conta ou criar novas chaves.

🔥 Os recebimentos vão direto para a sua conta, não possuimos saques pois não retemos os pagamentos.

🔥 Controle seus pagamentos por sua loja ou através do nosso dashboard em tempo real.

🔥 Crie links de pagamento personalizados e envie para os seus clientes por WhatsApp ou Email.

== O que o plugin da PixNew faz ==

✅ Adiciona um Gateway de pagamento dedicado a pagamentos via Pix para o WooCommerce.

✅ Permite a atualização dos status dos pedidos automaticamente, eliminando a necessidade de envio de comprovantes.

✅ Gera um QR code para pagamento PIX na finalização da compra. 

=== Como funciona: ===

👉 Cadastre-se na [PixNew](https://pixnew.com.br).

👉 Instale o Plugin.

👉 Cadastre o seu link de pagamento gerado na PixNew.

👉 Ao finalizar a compra, é gerado um QR code ou o cliente é redirecionado para o link de pagamento, você decide.

👉 O cliente efetua o pagamento e no mesmo instante é notificado sobre a confirmação de pagamento na tela.

=== Adicionais: ===

⭐️ Você pode definir um prazo de expiração, com contagem regressiva na tela de pagamento. Para isso, crie um novo link na PixNew e configure o tempo de expiração desejado.

⭐️ Ao expirar o pagamento, o status do pedido será alterado para FALHA. Conforme o [fluxo de pedidos do WooCommerce](https://woocommerce.com/document/managing-orders/)

⭐️ A baixa de estoque é realizada somente depois de confirmado o pagamento

⭐️ Após pedido gerado, é criado um novo campo personalizado com o link do pagamento, o novo campo se chama *payment_link_pix_new* e pode ser utilizado em outras áreas da sua loja ou para mandar no email ao cliente.


=== PERGUNTAS FREQUENTES ===

O PLUGIN POSSUI CUSTO OU MENSALIDADE?
* Não, o plugin é 100% grátis, você só paga um [percentual](https://pixnew.com.br/taxas/) por Pix recebido.

HÁ PAGAMENTO MÍNIMO PARA OS PEDIDOS? 
 * Sim, o pagamento mínimo de um pedido deve ser de R$5,00

POSSUI AMBIENTE DE TESTES?
* Ainda não mas irá possuir.

POSSO UTILIZAR O PLUGIN PIX JUNTO COM OUTROS GATEWAYS DE PAGAMENTO?
* Sim, o plugin pode ser utilizado junto com outros gateways de pagamento.


=== Changelog ===

- 2022.11 - version 1.1.1
    * FIX: Correção de alteração do status na expiração do pagamento mesmo após o pedido já estar aprovado.
    * NEW: Compatibilidade com WP 6.1

- 2022.10 - version 1.1.0
    * FIX: Correção de alteração do status na expiração do pagamento mesmo após o pedido já estar aprovado.

- 2022.09 - version 1.0.9
    * FIX: Novo pagamento gerado nos detalhes do pedido após pagamento anterior for expirado
    * FIX: Fix para pegar exatamente a url de webhook

- 2022.09 - version 1.0.8
    * FIX: Compatibilidade com plugins de páginas de Obrigado
    * NEW: QrCode exibido nos detalhes do pedido na conta do cliente

- 2022.09 - version 1.0.7
    * NEW: Alteração de status para FAILED quando o QRCODE expira

- 2022.09 - version 1.0.6
    * FIX: Validação do link de pagamento, com ou sem https://

- 2022.09 - version 1.0.5
    * FIX: Corrigido status do pedido de on-hold para pending-payment

- 2022.09 - version 1.0.4
    * FIX: Corrigido a atualização automática do status quando o pagamento é confirmado

- 2022.09 - version 1.0.3
    * NEW: Implementado opção de Pagamento Pix Transparente

- 2022.08 - version 1.0.2
    * FIX: Webhook para atualizar o pagamento automaticamente.
    * FIX: Redirecionamento para página de sucesso personalizada.
    * FIX: Logotipo do PIX

- 2022.08 - version 1.0.0
    * Lançamento inicial

=== Suporte ===

Caso encontre problemas, dificuldades ou queira sugerir alguma nova implementação não hesite em abrir uma [thread](https://wordpress.org/support/plugin/pagamentos-via-pix-pixnew/) no Suporte do Plugin ou enviar um e-mail para **[hello@pixnew.com.br](mailto:hello@pixnew.com.br)**.

