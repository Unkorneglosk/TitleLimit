<?php

// Let's tell to Vanilla "YEAH WE ARE BOLD AND WE LOVE UNICORNS".
$PluginInfo['titleLimit'] = array(
    'Name' => 'Title Limit',
    'Description' => "This plugin limits the number of words in discussions title. Code based on R_J's work.",
    'Version' => '1.3',
    'Author' => "Kube17",
    'SettingsUrl' => '/plugin/titlelimit',
    'AuthorEmail' => 'bobbamac@hotmail.fr',
    'AuthorUrl' => "http://kube17.tk",
    'License' => 'GNU GPLv2'
);

class TitleLimitPlugin extends Gdn_Plugin {
    public function setup() {
        // Add default configuration. If it's already set, he use the config.php value.
        touchConfig('TitleLimit.MaxTitleWords', 5);
    }

    public function pluginController_titleLimit_create($sender) {
        //Asks Garden for making a settings page. Good boy.
        $sender->permission('Garden.Settings.Manage');
        $sender->addSideMenu('dashboard/settings/plugins');
        $sender->setData('Title', t('Title Limit Settings')); // I've try to make a french locale with this. Not working WHY ? :'(
        $conf = new ConfigurationModule($sender);
        $conf->initialize(
            array(
                'TitleLimit.MaxTitleWords' => array(
                    'Control' => 'textbox',
                    'LabelCode' => 'Word limit in a discussion title.',
                    'Default' => '5'
                )
            )
        );
        $conf->renderAll();
    }

    public function discussionModel_beforeSaveDiscussion_handler($sender, $args) {
        if (str_word_count($args['FormPostValues']['Name']) > c('TitleLimit.MaxTitleWords', 5)) {
            // If the default configuration failed, the plugin use 5 words max.
            // This line alert users when the title contain more than X words.
            // Can be translated.
            $sender->Validation->addValidationResult(
                'Title',
                sprintf(
                    t(
                        'TitleLimit UserAlert',
                        'Your title is too long. Title must contain %1$s words or less.'
                    ),
                    c('TitleLimit.MaxTitleWords')
                )
            );
        }

    }
}
//Dont forget to end witha blank line. The next line looks like my life. Empty :'(