<header>
  <p class="user__title"><a href="/user_admin/"><img src="/user/common/images/theme/title_cms.png" alt="CATERS CMS"></a>
  </p>
  <h2><?= $user_site_list[$current_site_id]; ?>
  </h2>
  <div class="status">
    <a href="/" target="_blank"><i class="glyphs-pc"></i><span class="link_logout btn">サイト表示</span></a>
    <a href="/user_admin/logout" class="logout"><i class="glyphs-logout"></i><span
        class="link_logout btn">ログアウト</span></a>
  </div>
</header>