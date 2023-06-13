<table class="table" border="1">
    <tr>
        <td>CLIENTE: {{ $pedido->cliente->nombre_completo }}</td>
        <td></td>
        <td>FECHA PEDIDO: {{ $pedido->fecha_pedido }}</td>
    </tr>
    <tr>
        <td colspan="3">
            <h5>Productos</h5>
        </td>
    </tr>
    <tr>
        <td>NOMBRE</td>
        <td>PRECIO</td>
        <td>CANTIDAD</td>
    </tr>
    @foreach ($pedido->productos as $prod)
    <tr>
        <td>{{ $prod->nombre }}</td>
        <td>{{ $prod->precio }}</td>
        <td>{{ $prod->pivot->cantidad }}</td>
    </tr>        
    @endforeach
</table>

<style>
    .table{
        width: auto 100%;
    }
</style>