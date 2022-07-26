<?php if ($this->Paginator->hasPrev() || $this->Paginator->hasNext()):?>
<div class="p-information__pagi">
  <ul class="b-pagination">
    <?= $this->Paginator->prev() ?>
    <?= $this->Paginator->numbers(['modulus' => 4]);?>
    <?= $this->Paginator->next() ?>
  </ul>
</div>
<?php endif; ?>
<!-- <div class="p-information__pagi">
  <ul class="b-pagination">
    <li class="prev dissable">
      <a href="#"><img src="/assets/images/common/arr_right_lg_pink.png?v=eff8a01b89918aac0c2b0fa10d34498c" alt="" width="61" height="11" loading="lazy" decoding="async"/>
      </a>
    </li>
    <li class="active"><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li class="next"><a href="#"><img src="/assets/images/common/arr_right_lg_pink.png?v=eff8a01b89918aac0c2b0fa10d34498c" alt="" width="61" height="11" loading="lazy" decoding="async"/></a></li>
  </ul>
</div> -->