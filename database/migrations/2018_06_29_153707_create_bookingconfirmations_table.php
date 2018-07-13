<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingconfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookingconfirmations', function (Blueprint $table) {

            $table->increments('id');
            //Es el número identificador del comercio en el sistema de PayU, este número lo encontrará en el correo de creación de la cuenta.

            $table->integer('response_code_pol')->nullable();

            //Indica el estado de la transacción en el sistema.
            $table->string('phone')->nullable();

            //El riesgo asociado a la transacción. Toma un valor entre 0 y 1. A mayor riesgo mayor valor. Viene en formato ###.00
            $table->string('additional_value')->nullable();

            //El código de respuesta de PayU.
            $table->string('test')->nullable();

           //Es la referencia de la venta o pedido. Deber ser único por cada transacción que se envía al sistema.
            $table->string('transaction_date')->nullable();

            //La referencia o número de la transacción generado en PayU.
            $table->string('cc_number')->nullable();

            //Es la firma digital creada para cada uno de las transacciones.
            $table->string('cc_holder')->nullable();

            //Campo adicional para enviar información sobre la compra. Ej. Descripción de la compra en caso de querer visualizarla en la página de confirmación.
            $table->string('error_code_bank')->nullable();

            //Campo adicional para enviar información sobre la compra. Ej. Códigos internos de los productos.
            $table->string('billing_country')->nullable();

            //El identificador interno del medio de pago utilizado.
            $table->string('bank_referenced_name')->nullable();

            //El tipo de medio de pago utilizado para el pago
            $table->string('description')->nullable();

            //Número de cuotas en las cuales se difirió el pago con tarjeta crédito.
            $table->string('administrative_fee_tax')->nullable();

            //Es el monto total de la transacción. Puede contener dos dígitos decimales. Ej. 10000.00 ó 10000
            $table->string('value')->nullable();

            //Es el valor del IVA de la transacción, si se envía el IVA nulo el sistema aplicará el 19% automáticamente. Puede contener dos dígitos decimales. Ej: 19000.00. En caso de no tener IVA debe enviarse en 0.
            $table->string('administrative_fee')->nullable();

            //Valor Adicional no comisionable.
            $table->string('payment_method_type')->nullable();

            //La fecha en que se realizó la transacción.
            $table->string('office_phone')->nullable();

            //La moneda respectiva en la que se realiza el pago. El proceso de conciliación se hace en pesos a la tasa representativa del día.
            $table->string('email_buyer')->nullable();

            //Campo que contiene el correo electrónico del comprador para notificarle el resultado de la transacción por correo electrónico. Se recomienda hacer una validación si se toma este dato en un formulario.
            $table->string('response_message_pol')->nullable();

            //El cus, código único de seguimiento, es la referencia del pago dentro del Banco, aplica solo para pagos con PSE
            $table->string('error_message_bank')->nullable();

            //El nombre del banco, aplica solo para pagos con PSE.
            $table->string('shipping_city')->nullable();

            //Variable para poder identificar si la operación fue una prueba.
            $table->string('transaction_id')->nullable();

            //Es la descripción de la venta.
            $table->string('sign')->nullable();

            //La dirección de facturación
            $table->string('tax')->nullable();

            //La dirección de entrega de la mercancía.
            $table->string('payment_method')->nullable();

            //El teléfono de residencia del comprador.
            $table->string('billing_address')->nullable();

            //El teléfono diurno del comprador.
            $table->string('payment_method_name')->nullable();

            //Identificador de la transacción.
            $table->string('pse_bank')->nullable();

            //Identificador de la transacción.
            $table->string('state_pol')->nullable();

            //Valor de la tarifa administrativa
            $table->string('date')->nullable();

            //Valor base de la tarifa administrativa
            $table->string('nickname_buyer')->nullable();

            //Valor del impuesto de la tarifa administrativa
            $table->string('reference_pol')->nullable();

            //Código de la aerolínea
            $table->string('currency')->nullable();

            //Numero de intentos del envío de la confirmación.
            $table->string('risk')->nullable();

            //Código de autorización de la venta
            $table->string('shipping_address')->nullable();
            
            //Código de autorización de la agencia de viajes
            $table->string('bank_id')->nullable();

            //Identificador del banco
            $table->string('payment_request_state')->nullable();

            //La ciudad de facturación.
            $table->string('customer_number')->nullable();

            //El código ISO del país asociado a la dirección de facturación.
            $table->string('administrative_fee_base')->nullable();

            //Valor de la comisión
            $table->string('attempts')->nullable();

            //Moneda de la comisión
            $table->string('merchant_id')->nullable();

            //Numero de cliente.
            $table->string('exchange_rate')->nullable();

            //Fecha de la operación.
            $table->string('shipping_country')->nullable();

            //Código de error del banco.
            $table->string('installments_number')->nullable();

            //Mensaje de error del banco
            $table->string('franchise')->nullable();

            //Valor de la tasa de cambio.
            $table->string('extra1')->nullable();

            //Dirección ip desde donde se realizó la transacción.
            $table->string('extra2')->nullable();

            //Nombre corto del comprador.
            $table->string('antifraudMerchantId')->nullable();

            //Nombre corto del vendedor.
            $table->string('extra3')->nullable();

            //Identificador del medio de pago.
            $table->string('nickname_seller')->nullable();

            //Estado de la solicitud de pago.
            $table->string('ip')->nullable();

            //Referencia no. 1 para pagos con PSE.
            $table->string('airline_code')->nullable();

            //Referencia no. 2 para pagos con PSE.
            $table->string('billing_city')->nullable();

            //Referencia no. 3 para pagos con PSE.
            $table->string('pse_reference1')->nullable();

            //El mensaje de respuesta de PAYU.
            $table->string('reference_sale')->nullable();

            //La ciudad de entrega de la mercancía.
            $table->string('pse_reference3')->nullable(); 
            
            //El código ISO asociado al país de entrega de la mercancía.
            $table->string('pse_reference2')->nullable();  

             
            $table->timestamps();

                    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookingconfirmations');
    }
}
