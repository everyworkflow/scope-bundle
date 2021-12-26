/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React, { useEffect } from 'react';
import Tree, { DataNode } from 'antd/lib/tree';
import PageHeaderComponent from "@EveryWorkflow/AdminPanelBundle/Component/PageHeaderComponent";
import BreadcrumbComponent from "@EveryWorkflow/AdminPanelBundle/Component/BreadcrumbComponent";
import { useState } from 'react';
import Card from 'antd/lib/card';
import Remote from "@EveryWorkflow/PanelBundle/Service/Remote";
import AlertAction, { ALERT_TYPE_ERROR } from "@EveryWorkflow/PanelBundle/Action/AlertAction";

const ScopePageOld = () => {
    const [treeData, setTreeData] = useState<Array<DataNode>>([]);
    const [expandedKeys, setExpendedKeys] = useState<Array<string | number>>([]);
    const [selectedKey, setSelectedKey] = useState<string | number | undefined>(undefined);

    useEffect(() => {
        const generateTreeData = (data: Array<any>, code: string | number | undefined = undefined): DataNode[] => {
            const items: DataNode[] = [];
            data.forEach((item: any) => {
                if (item.parent === code) {
                    items.push({
                        title: item.code + ' - ' + item.name,
                        key: item.code,
                        children: generateTreeData(data, item.code),
                    });
                }
            });
            return items;
        };
        const handleResponse = (response: any) => {
            setTreeData(generateTreeData(response.items));
        };

        const fetchItem = async () => {
            try {
                const response: any = await Remote.get('/scope/all');
                handleResponse(response);
            } catch (error: any) {
                AlertAction({
                    message: error.message,
                    title: 'Fetch error',
                    type: ALERT_TYPE_ERROR,
                });
            }
        };

        fetchItem();
    }, []);

    const onDragEnter = (info: any) => {
        console.log('onDragEnter -->', info);
        // expandedKeys 需要受控时设置
        // this.setState({
        //   expandedKeys: info.expandedKeys,
        // });
    };

    const onDrop = (info: any) => {
        console.log('onDrop --->', info);
    };

    return (
        <>
            <PageHeaderComponent
                title={'Scope'}
            />
            <BreadcrumbComponent />
            <Card
                className="app-container"
                style={{ marginBottom: 24 }}
            >
                <Tree
                    className="draggable-tree"
                    defaultExpandedKeys={expandedKeys}
                    draggable
                    blockNode
                    onDragEnter={onDragEnter}
                    onDrop={onDrop}
                    treeData={treeData}
                    onSelect={(selectedKeys: Array<string | number>) => {
                        if (selectedKeys.length === 1) {
                            setSelectedKey(selectedKeys[0]);
                        } else {
                            setSelectedKey(undefined);
                        }
                    }}
                />
            </Card>
        </>
    );
};

export default ScopePageOld;
