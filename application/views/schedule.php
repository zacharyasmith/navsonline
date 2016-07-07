<?php $this->load->view('template/header'); ?>

<!-- content -->
<div id="main_content" class="ui stackable grid">

    <!-- main content -->
    <div class="ui eleven wide column">

        <div class="ui segment">
            <!-- welcome message -->
            <div class="ui grid">
                <div class="twelve wide column">
                    <h1 class="ui header">
                        Welcome, <?= $user['first_name'] . ' ' . $user['last_name'] ?>
                        <div class="sub header">Here is the upcoming schedule</div>
                    </h1>
                </div>
                <div class="four wide column">
                    <?php if ($auth_level >= 9): //admin required. modal included below ?>
                        <button class="ui button green basic tiny" id="event_new_modal">
                            <i class="add square icon"></i>
                            Add new event
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- spacer -->
            <div style="width: 100%; height: 30px; display: block;"></div>

            <div class="ui grid">
                <!-- agenda table -->
                <table class="ui very basic table">
                    <thead>
                        <tr>
                            <th class="">Event</th>
                            <th class="">Date</th>
                            <th class="">Your Role</th>
                            <th class="">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- agenda template -->
                        <tr>
                            <td>
                                <a href="#">
                                    Nav Night
                                </a>
                            </td>
                            <td>Aug 14th</td>
                            <td>Lead Guitar, Vocals</td>
                            <td>
                                <div class="ui icon buttons tiny">
                                    <button class="ui button basic blue tiny navs_popup" data-content="View" data-position="top center">
                                        <i class="unhide icon"></i>
                                    </button>
                                    <button class="ui button basic blue tiny navs_popup" data-content="Edit (admin)" data-position="top center">
                                        <i class="write icon"></i>
                                    </button>
                                </div>
                                <div class="ui icon buttons tiny">
                                    <button class="ui button basic green tiny navs_popup" data-content="Confirm" data-position="top center">
                                        <i class="check icon"></i>
                                    </button>
                                    <button class="ui button basic red tiny navs_popup" data-content="Deny" data-position="top center">
                                        <i class="close icon"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- end template -->
                        <!-- agenda template -->
                        <tr>
                            <td>
                                <a href="#">
                                    Nav Night
                                </a>
                            </td>
                            <td>Aug 14th</td>
                            <td>Lead Guitar, Vocals</td>
                            <td>
                                <div class="ui icon buttons tiny">
                                    <button class="ui button basic blue tiny navs_popup" data-content="View" data-position="top center">
                                        <i class="unhide icon"></i>
                                    </button>
                                    <button class="ui button basic blue tiny navs_popup" data-content="Edit (admin)" data-position="top center">
                                        <i class="write icon"></i>
                                    </button>
                                </div>
                                <div class="ui icon buttons tiny">
                                    <button class="ui button basic green tiny navs_popup" data-content="Confirm" data-position="top center">
                                        <i class="check icon"></i>
                                    </button>
                                    <button class="ui button basic red tiny navs_popup" data-content="Deny" data-position="top center">
                                        <i class="close icon"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- end template -->
                        <!-- agenda template -->
                        <tr>
                            <td>
                                <a href="#">
                                    Nav Night
                                </a>
                            </td>
                            <td>Aug 14th</td>
                            <td>Lead Guitar, Vocals</td>
                            <td>
                                <div class="ui icon buttons tiny">
                                    <button class="ui button basic blue tiny navs_popup" data-content="View" data-position="top center">
                                        <i class="unhide icon"></i>
                                    </button>
                                    <button class="ui button basic blue tiny navs_popup" data-content="Edit (admin)" data-position="top center">
                                        <i class="write icon"></i>
                                    </button>
                                </div>
                                <div class="ui icon buttons tiny">
                                    <button class="ui button basic green tiny navs_popup" data-content="Confirm" data-position="top center">
                                        <i class="check icon"></i>
                                    </button>
                                    <button class="ui button basic red tiny navs_popup" data-content="Deny" data-position="top center">
                                        <i class="close icon"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- end template -->
                    <tfoot>
                        <tr><th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <?php $this->load->view('template/sidebar'); ?>
</div>


<?php
if ($auth_level >= 9):
    $this->load->view('modal/event_new');
endif;

$this->load->view('template/footer');
