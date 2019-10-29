<thead>
<tr>
  <th class="text-center">наименование</th>
  <th class="text-center">цена</th>
  <th class="text-center">количество</th>
  <th class="text-center">стоимость</th>
</tr>
</thead>
<tbody>
{foreach from=$order_data.order.services item=item}
  <tr>
    <td>{$item.name}</td>
    <td class="text-right">{$item.price|number_format:2:".":"'"|default:"0"}</td>
    <td class="text-right">{$item.qty|number_format:2:".":"'"|default:"0"}</td>
    <td class="text-right">{$item.cost|number_format:2:".":"'"|default:"0"}</td>
  </tr>
{/foreach}
</tbody>