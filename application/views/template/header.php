<!DOCTYPE html>
<html>
<head>
  <title><?= isset($title) ? $title . ' | ' : '' ?>NavsOnline</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/png" href="<?= base_url(); ?>logo/favicon.png"/>
  <script src='<?= base_url(); ?>js/jquery/dist/jquery.min.js'></script>
  <script src='<?= base_url(); ?>js/jquery.maskedinput-1.3.min.js'></script>
  <script src='<?= base_url(); ?>js/jquery/jquery-dateFormat.min.js'></script>
  <script src="<?= base_url(); ?>style/semantic/dist/semantic.min.js"></script>
  <link rel="stylesheet" href="<?= base_url(); ?>style/semantic/dist/semantic.min.css">

  <style type="text/css">
  body {
    background-color: #104d79;
  }
  #top_menu .menu .item {
    padding: 23px 20px;
  }
  #main_content{
    max-width: 1100px;
    margin: auto auto;
  }
  .nav_italic{
    font-style: italic !important;
  }
  #flash-container {
    position: absolute;
    width: 350px;
    z-index: 100;
    top: 10px;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
  }
  </style>

  <script>
  $(document).ready(function() {
    $('.message .close')
    .on('click', function() {
      $(this)
      .closest('.message')
      .transition('fade');
    });
    setTimeout(function () {
      $('.flash').transition('fade');
    }, 5000)

    $('.navs_popup').popup();
    $('.dropdown').dropdown();
    $('.ui.radio.checkbox').checkbox();
    $('.dropdown.additions').dropdown({
      allowAdditions: true
    });
    $('.nav_tags').dropdown({
      allowAdditions: true
    });
    $('.nav_phone').mask("(999) 999-9999");
    setTimeout(function() {
      $('.dismissing_message')
      .closest('.message')
      .transition('fade');
    }, 3000);
    //search functions
    $('.ui.search.media_chord')
    .search({
      apiSettings: {
        url: '<?= base_url('/ajax/media-search'); ?>?type=chord&q={query}'
      },
      cache: false,
      onSelect: function(result, response) {
        $(this).next().val(JSON.stringify(result));
      }
    });
    $('.ui.search.media_lyrics')
    .search({
      apiSettings: {
        url: '<?= base_url('/ajax/media-search'); ?>?type=lyric&q={query}'
      },
      cache: false,
      onSelect: function(result, response) {
        $(this).next().val(JSON.stringify(result));
      }
    });
    $('.ui.search.media_audio')
    .search({
      apiSettings: {
        url: '<?= base_url('/ajax/media-search'); ?>?type=audio&q={query}'
      },
      cache: false,
      onSelect: function(result, response) {
        $(this).next().val(JSON.stringify(result));
      }
    });
    $('.ui.search.user_search')
    .search({
      apiSettings: {
        url: '<?= base_url('/ajax/user-search'); ?>?q={query}'
      },
      cache: false,
      onSelect: function(result, response) {
        $(this).next().val(JSON.stringify(result));
      }
    });
    $('.ui.search.user_search.restricted_search')
    .search({
      apiSettings: {
        url: '<?= base_url('/ajax/user-search'); ?>?q={query}&restricted={eid}'
      },
      cache: false,
      onSelect: function(result, response) {
        $(this).next().val(JSON.stringify(result));
      }
    });
    //arrangement search
    $(".a_search_key").hide();
    $('.ui.search.arrangement_search')
    .search({
      apiSettings: {
        url: '<?= base_url('/ajax/arrangement-search'); ?>?q={query}'
      },
      cache: false,
      onSelect: function(result, response) {
        //select parent field for search_key
        var parent = $(this).parent(".field");
        var a_search_key = parent.next(".a_search_key");
        //set the hidden field value
        $(this).next().val(JSON.stringify(result));
        //show the search field
        a_search_key.show();
        var keys = JSON.parse(result.keys);
        a_search_key.find(".nav.menu").html('');
        //run through the keys and set the dropdown
        $.each(keys, function(key, value) {
          if (value.key !== 'Open') {
            var html = '<div class="item" data-value="' + value.key + '">' + value.key + '</div>';
            a_search_key.find(".nav.menu").append(html);
          }
        });
        //select the default key
        a_search_key.children(".dropdown").dropdown('clear');
        a_search_key.children(".dropdown").dropdown('set text', result.default);
        a_search_key.children(".dropdown").dropdown('set value', result.default);
      }
    });
    $(".search.song_search")
    .search({
      apiSettings: {
        url: '<?= base_url('/ajax/song-search'); ?>?q={query}'
      },
      cache: false
    });
    //confirm modal
    $(".confirm_api").click(function() {
      var curr_button = this;
      $('.confirm_modal')
      .modal({allowMultiple: true})
      .modal('setting', 'closable', false)
      .modal('show')
      .modal({
        onApprove: function() {
          $(curr_button)
          .api({
            on: 'now',
            onResponse: function(response) {
              if (response && response.success) {
                if (typeof response.reload !== 'undefined') {
                  //if reload is set
                  location.reload();
                } else {
                  if (!$(curr_button).hasClass('no_delete'))
                  $(curr_button).closest('tr').remove();
                }
              } else {
                $(curr_button).state('flash text', 'Error!');
              }
            }
          });
          return true;
        }
      });
    });
  });
  //API setup
  $.fn.api.settings.api = {
    'event confirm': '<?= base_url('/ajax/event-confirm'); ?>/{eid}/{uid}',
    'event deny': '<?= base_url('/ajax/event-deny'); ?>/{eid}/{uid}',
    'event delete': '<?= base_url('/ajax/event-delete'); ?>/{eid}',
    'event item delete': '<?= base_url('/ajax/event-item-delete'); ?>/{eiid}',
    'song delete': '<?= base_url('/ajax/song-delete'); ?>/{sid}',
    'event person delete': '<?= base_url('/ajax/event-person-delete'); ?>/{eid}/{uid}',
    'arrangement delete': '<?= base_url('/ajax/arrangement-delete'); ?>/{aid}',
    'blockout add': '<?= base_url('/ajax/blockout-add'); ?>',
    'blockout delete': '<?= base_url('/ajax/blockout-delete'); ?>/{uid}/{db}/{de}',
    'user ban': '<?= base_url('/ajax/user-role'); ?>/?update=true&uid={uid}&role=denied',
    'user archive': '<?= base_url('/ajax/user-role'); ?>/?update=true&uid={uid}&role=archived',
    'user make viewer': '<?= base_url('/ajax/user-role'); ?>/?update=true&uid={uid}&role=viewer',
    'user make admin': '<?= base_url('/ajax/user-role'); ?>/?update=true&uid={uid}&role=admin'
  };
  </script>
</head>
<body>
  <div id="top_menu" class="ui inverted fixed top segment" style="background: #0b1426;">
    <div class="ui inverted secondary stackable pointing menu" style="border: none;">
      <!-- LOGO -->
      <a class="ui image small" style="margin-top: 11px;" href="<?= base_url('') ?>">
        <img src="<?= base_url(); ?>logo/navsonline_400x108.png" class="image">
      </a>
      <!-- SCHEDULE -->
      <?php if (strpos(uri_string(), 'event/') !== FALSE && strpos(uri_string(), 'user/schedule/') === false): ?>
        <div class="item active" style="margin-left:25px;">
          <div class="ui breadcrumb">
            <a href="<?= base_url('user/schedule'); ?>">
              <i class="unordered list icon"></i>
              Schedule
            </a>
            <div class="divider" style="color: white;"> / </div>
            <div class="section"><?= $title ?></div>
          </div>
        </div>
      <?php else: ?>
        <a class="item <?= strpos(uri_string(), 'user/schedule') !== FALSE || uri_string() == '' ? 'active' : '' ?>" style="margin-left:25px;" href="<?= base_url('user/schedule'); ?>">
          <i class="unordered list icon"></i>
          Schedule
        </a>
      <?php endif; ?>
      <!-- SONGS -->
      <?php if (strpos(uri_string(), 'music/') !== FALSE && strpos(uri_string(), 'user/music/') === false): ?>
        <div class="item active">
          <div class="ui breadcrumb">
            <a href="<?= base_url('user/music'); ?>">
              <i class="music icon"></i>
              Songs
            </a>
            <div class="divider" style="color: white;"> / </div>
            <div class="section"><?= $title ?></div>
          </div>
        </div>
      <?php else: ?>
        <a class="item <?= strpos(uri_string(), 'user/music') !== FALSE ? 'active' : '' ?>" href="<?= base_url('user/music'); ?>">
          <i class="music icon"></i>
          Songs
        </a>
      <?php endif; ?>
      <!-- PEOPLE -->
      <a class="item <?= uri_string() == 'user/people' ? 'active' : '' ?>" href="<?= base_url('user/people'); ?>">
        <i class="users icon"></i>
        People
      </a>
      <div class="ui secondary inverted stackable right pointing menu" style="border: none;">
        <a class="ui item <?= uri_string() == 'user/preferences' ? 'active' : '' ?>" href="<?= base_url('user/preferences'); ?>">
          <i class="setting icon"></i>
          Settings
        </a>
        <a class="ui item <?= uri_string() == 'user/logout' ? 'active' : '' ?>" href="<?= base_url('user/logout'); ?>">
          <i class="sign out icon"></i>
          Logout
        </a>
      </div>
    </div>
  </div>

  <?php if($this->session->flashdata()): // flash messages ?>
    <div id="flash-container" >
      <?php foreach ($this->session->flashdata() as $flash):  ?>
        <div class="ui negative message flash">
          <i class="close icon"></i>
          <?= $flash ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
