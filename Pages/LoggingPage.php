<?php
namespace Grout\Cyantree\LoggingModule\Pages;

use Cyantree\Grout\App\Page;
use Cyantree\Grout\App\Types\ContentType;
use Cyantree\Grout\Tools\StringTools;
use Grout\Cyantree\LoggingModule\LoggingModule;

class LoggingPage extends Page
{
    public function parseTask()
    {
        /** @var $m LoggingModule */
        $m = $this->task->module;

        $mode = $this->request()->get->get('mode');
        $status = null;

        if ($mode == 'clear') {
            file_put_contents($m->moduleConfig->file, '');
            $status = 'All logs have been cleared.';

        }

        $logs = is_file($m->moduleConfig->file) ? file_get_contents($m->moduleConfig->file) : '';

        $logs = StringTools::escapeHtml($logs);


        $content = <<<CNT
<!DOCTYPE html>
<body>
<div>
<a href="?">Show logs</a>
<a href="?mode=clear">Clear logs</a>
</div>
<div><strong>
{$status}
</strong></div>
<hr>
<pre>
{$logs}
</pre>
</body>
CNT;

        $this->task->response->postContent($content, ContentType::TYPE_HTML_UTF8);
    }
}
