<!DOCTYPE html>
<html>
    <head>
        <title><?= isset($title) ? $title . ' | ' : '' ?>NavsOnline</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="<?= base_url(); ?>logo/favicon.png"/>
        <script src='<?= base_url(); ?>js/jquery/dist/jquery.min.js'></script>
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
        </style>

        <script>
            $(document).ready(function() {
                $('.navs_popup').popup();
                $('.dropdown').dropdown();
                $('.dropdown.additions').dropdown({
                    allowAdditions: true
                });
                $('.nav_tags').dropdown({
                    allowAdditions: true
                });

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

                //confirm modal
                $(".confirm_api").click(function() {
                    var curr_button = this;
                    $('.confirm_modal')
                            .modal('setting', 'closable', false)
                            .modal('show')
                            .modal({
                                onApprove: function() {
                                    $(curr_button)
                                            .api({
                                                on: 'now',
                                                onResponse: function(response) {
                                                    if (response && response.success) {
                                                        $(curr_button).closest('tr').remove();
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
                'song delete': '<?= base_url('/ajax/song-delete'); ?>/{sid}',
                'arrangement delete': '<?= base_url('/ajax/arrangement-delete'); ?>/{aid}',
                'blockout add': '<?= base_url('/ajax/blockout-add'); ?>',
                'blockout delete': '<?= base_url('/ajax/blockout-delete'); ?>/{uid}/{db}/{de}'
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
                <a class="item <?= uri_string() == 'user/schedule' || uri_string() == '' ? 'active' : '' ?>" style="margin-left:25px;" href="<?= base_url('user/schedule'); ?>">
                    <i class="unordered list icon"></i>
                    Schedule
                </a>
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
                    <a class="item <?= uri_string() == 'user/music' || strpos(uri_string(), 'user/music/') !== FALSE ? 'active' : '' ?>" href="<?= base_url('user/music'); ?>">
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
