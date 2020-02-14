<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class notificationTest extends TestCase
{
    public function testAddNotificationWithCorrectParameter(): void
    {
        $this->assertEquals(
            "INSERT INTO `` ( project_id, user_id, group_id, type, message, data ) VALUES ( 1, 1, 0, 'brand_reviewed', 'sample text', '{\"task_id\":123}' )",
            notification::_query_add(array(
                "project_id"=>1,
                "user_id"=>1,
                "group_id"=>0,
                "type"=>"brand_reviewed",
                "message"=>"sample text",
                "data"=>array(
                    "task_id"=>123
                )
            ))
        )
    }
}

