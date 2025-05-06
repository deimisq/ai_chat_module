<?php
// Define the AI Chat Module
class AiChatModule extends CModule {
    public function init() {
        $this->name = _('AI Chat');
        $this->description = _('Chat module powered by AI for Zabbix.');
        $this->version = '1.0.0';
        $this->widgets = [
            'ai_chat_widget' => _('AI Chat')
        ];
    }
}
