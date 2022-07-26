 <!-- </tbody> -->
 <tr>
   <td class="head m-0 p-0" colspan="2">
     <button class="btn w-100 btn-light" type="button" data-toggle="collapse" data-target="#optionMetaItem"
       aria-expanded="false">
       <span>metaタグ</span> <i class="fas fa-angle-down"></i>
     </button>
   </td>
 </tr>

 <tbody id="optionMetaItem" class="collapse">
   <tr>
     <td>meta
       <div>(ページ説明文)</div>
     </td>
     <td>
       <?= $this->Form->input('meta_description', ['type' => 'textarea', 'maxlength' => '200', 'style' => '']); ?>
       <br><span>※200文字まで</span>
     </td>
   </tr>

   <?php if (false): ?>
   <tr>
     <td>meta
       <div>(キーワード)</div>
     </td>
     <td>
       <?php for ($i = 0;$i < 5;$i++): ?>
       <div><?= ($i + 1); ?>.<?= $this->Form->input("keywords.{$i}", ['type' => 'text', 'maxlength' => '20', 'style' => '']); ?>
       </div>
       <?php endfor; ?>
       <br><span>※各20文字まで</span>
     </td>
   </tr>
   <?php endif;?>
 </tbody>