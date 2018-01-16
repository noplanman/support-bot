<?php declare(strict_types=1);
/**
 * This file is part of the PHP Telegram Support Bot.
 *
 * (c) PHP Telegram Bot Team (https://github.com/php-telegram-bot)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\ServerResponse;

/**
 * User "/help" command
 *
 * Command that lists all available commands and displays them in User and Admin sections.
 */
class HelpCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'help';

    /**
     * @var string
     */
    protected $description = 'Show bot commands help';

    /**
     * @var string
     */
    protected $usage = '/help or /help <command>';

    /**
     * @var string
     */
    protected $version = '0.1.0';
    /**
     * @inheritdoc
     */
    public function execute(): ServerResponse
    {
        $message     = $this->getMessage();
        $chat_id     = $message->getChat()->getId();
        $command_str = trim($message->getText(true));
        
        
        $text = <<<EOT
Hello

Please feel free to ask your Questions about the PHP Telegram Bot Library.
Keep in mind that this Channel is English only. 

Below you can see the aviable Commands of this Bot

EOT;
		
        // Admin commands shouldn't be shown in group chats
        $safe_to_show = $message->getChat()->isPrivateChat();
        $data = [
			'text'		 => $text . PHP_EOL,
            'chat_id'    => $chat_id,
            'parse_mode' => 'markdown',
        ];
        [$all_commands, $user_commands, $admin_commands] = $this->getUserAdminCommands();
        // If no command parameter is passed, show the list.
        if ($command_str === '') {
            $data['text'] .= '*Commands List*:' . PHP_EOL;
            foreach ($user_commands as $user_command) {
                $data['text'] .= '/' . $user_command->getName() . ' - ' . $user_command->getDescription() . PHP_EOL;
            }
            if ($safe_to_show && count($admin_commands) > 0) {
                $data['text'] .= PHP_EOL . '*Admin Commands List*:' . PHP_EOL;
                foreach ($admin_commands as $admin_command) {
                    $data['text'] .= '/' . $admin_command->getName() . ' - ' . $admin_command->getDescription() . PHP_EOL;
                }
            }
            $data['text'] .= PHP_EOL . 'For exact command help type: /help <command>';
            return Request::sendMessage($data);
        }
        $command_str = str_replace('/', '', $command_str);
        if (isset($all_commands[$command_str]) && ($safe_to_show || !$all_commands[$command_str]->isAdminCommand())) {
            $command      = $all_commands[$command_str];
            $data['text'] = sprintf(
                'Command: %s (v%s)' . PHP_EOL .
                'Description: %s' . PHP_EOL .
                'Usage: %s',
                $command->getName(),
                $command->getVersion(),
                $command->getDescription(),
                $command->getUsage()
            );
            return Request::sendMessage($data);
        }
        $data['text'] = 'No help available: Command /' . $command_str . ' not found';
        return Request::sendMessage($data);
    }
    /**
     * Get all available User and Admin commands to display in the help list.
     *
     * @return Command[][]
     */
    protected function getUserAdminCommands(): array
    {
        // Only get enabled Admin and User commands that are allowed to be shown.
        /** @var Command[] $commands */
        $commands = array_filter($this->telegram->getCommandsList(), function ($command) {
            /** @var Command $command */
            return !$command->isSystemCommand() && $command->showInHelp() && $command->isEnabled();
        });
        ksort($commands);

        $user_commands = array_filter($commands, function ($command) {
            /** @var Command $command */
            return $command->isUserCommand();
        });
        ksort($user_commands);

        $admin_commands = array_filter($commands, function ($command) {
            /** @var Command $command */
            return $command->isAdminCommand();
        });
        ksort($admin_commands);

        return [$commands, $user_commands, $admin_commands];
    }
}
