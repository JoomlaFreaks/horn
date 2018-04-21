<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\EventSubscriber;
use YOOtheme\Theme\Builder;

class ContentListener extends EventSubscriber
{
    const PATTERN = '/^<!-- (\{.*\}) -->/';

    protected $user;
    protected $edit;
    protected $isArticle;
    protected $inject = [
        'db' => 'app.db',
        'admin' => 'app.admin',
        'routes' => 'app.routes',
        'customizer' => 'theme.customizer',
        'session' => 'JFactory::getSession',
        'application' => 'JFactory::getApplication',
    ];

    public function onInit($theme)
    {
        $this->user = \JFactory::getUser();
        $this->routes->post('/page', [$this, 'savePage']);
    }

    public function onSite($theme)
    {
        $input = $this->application->input;

        $this->isArticle = $input->getCmd('option') == 'com_content' && $input->getCmd('view') == 'article' && $input->getCmd('task') == null;

        if ($this->isArticle
            AND $this->customizer->isActive()
            AND $theme->params->get('admin')
            AND $user_id = $theme->params->get('user_id')
        ) {
            $this->session->set('user', \JFactory::getUser($user_id));
        }
    }

    public function onDispatch($document, $input)
    {
        $this->session->set('user', $this->user);

        if ($this->admin || !$this->isArticle) {
            return;
        }

        if (!$article = \JControllerLegacy::getInstance('Content')->getView('article', 'html')->get('Item') OR !$article->params->get('access-view')) {
            return;
        }

        $edit = '';
        $content = preg_match(self::PATTERN, $article->fulltext, $matches) ? json_decode($matches[1], true) : null;

        if ($article->params->get('access-edit')) {

            if ($this->customizer->isActive()) {

                if ($page = $this->theme->params->get('page')) {
                    $content = $page['content'];
                }

                if ($content) {
                    $content = Builder::encode($content, false);
                }

                $data = [
                    'id' => $article->id,
                    'catid' => $article->catid,
                    'title' => $article->title,
                    'content' => $content,
                    'modified' => !empty($page),
                ];

                $this->customizer->addData('page', $data);

            } else {

                $url = \JRoute::_(\ContentHelperRoute::getFormRoute($article->id).'&return='.base64_encode(\JUri::getInstance()));

                $edit = "<a style=\"position: fixed!important\" class=\"uk-position-medium uk-position-bottom-right uk-button uk-button-primary\" href=\"{$url}\">".\JText::_('JACTION_EDIT')."</a>";
            }

        }

        $this->theme->set('builder', $content !== null ? compact('content', 'edit') : null);
    }

    public function savePage($page)
    {
        jimport('legacy.model.legacy');

        if (!$page or !$page = base64_decode($page) or !$page = json_decode($page, true)) {
            $this->app->abort(500, 'Something went wrong.');
        }

        $data = [
            'id' => $page['id'],
            'catid' => $page['catid'],
            'title' => $page['title'],
            'introtext' => Builder::content($page['content']),
            'fulltext' => '<!-- '.Builder::encode($page['content']).' -->',
        ];

        \JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_content/models', 'ContentModel');

        $model = \JModelLegacy::getInstance('Article', 'ContentModel', ['ignore_request' => true]);
        $context = 'com_content.article';

        if (!defined('JPATH_COMPONENT')) {
            define('JPATH_COMPONENT', JPATH_BASE . '/components/com_ajax');
        }

        if (!$this->user->authorise('core.edit', "com_content.article.{$data['id']}")) {
            $this->app->abort(403, 'Insufficient User Rights.');
        }

        if ($tags = (new \JHelperTags)->getTagIds($data['id'], $context)) {
            $data['tags'] = explode(',', $tags);
        }

        if (class_exists('FieldsHelper')) {
            foreach (\FieldsHelper::getFields($context, $model->getItem($data['id'])) as $field) {
                $data['com_fields'][$field->name] = $field->value;
            }
        }

        $model->save($data);

        return 'success';
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => 'onInit',
            'theme.site' => 'onSite',
            'dispatch' => ['onDispatch', 10],
        ];
    }
}
