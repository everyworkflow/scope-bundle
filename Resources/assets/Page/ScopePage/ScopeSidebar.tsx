/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React, { useEffect, useState } from 'react';
import Tree from 'antd/lib/tree';
import Remote from "@EveryWorkflow/PanelBundle/Service/Remote";
import AlertAction, { ALERT_TYPE_ERROR } from "@EveryWorkflow/PanelBundle/Action/AlertAction";
import { useNavigate } from "react-router-dom";
import { EventDataNode } from "rc-tree/lib/interface";
import { DataNode } from 'antd/lib/tree';

const initTreeData: DataNode[] = [{ title: 'Default', key: 'default' }];

const ScopeSidebar = () => {
    const [treeData, setTreeData] = useState<Array<any>>(initTreeData);
    const navigate = useNavigate();

    useEffect(() => {
        onLoadData({ key: 'default' }).then();
    }, []);

    const updateTreeData = (list: DataNode[], key: React.Key, children: DataNode[]): DataNode[] => {
        return list.map(node => {
            if (node.key === key) {
                return {
                    ...node,
                    children,
                };
            }
            if (node.children) {
                return {
                    ...node,
                    children: updateTreeData(node.children, key, children),
                };
            }
            return node;
        });
    }

    const onLoadData = ({ key, children }: any) => {
        return new Promise<void>(resolve => {
            if (children) {
                resolve();
                return;
            }

            const mapToTreeData = (categoryItems: Array<any>): Array<any> => {
                return categoryItems.map((categoryItem: any) => {
                    let newObj: any = {
                        key: categoryItem.code,
                        title: categoryItem.name,
                        isLeaf: !(categoryItem.children_count > 0 || categoryItem.children?.length > 0),
                    };
                    if (categoryItem.children && Array.isArray(categoryItem.children)) {
                        newObj['children'] = mapToTreeData(categoryItem.children);
                    }
                    return newObj;
                });
            }

            const handleResponse = (response: any) => {
                let treeData: Array<any> = [];
                if (response.items && Array.isArray(response.items)) {
                    treeData = mapToTreeData(response.items);
                }
                setTreeData(origin => updateTreeData(origin, key, treeData));
            };

            const fetchTreeData = async () => {
                try {
                    const response: any = await Remote.get('/scope-tree/' + key);
                    handleResponse(response);
                } catch (error: any) {
                    AlertAction({
                        message: error.message,
                        title: 'Fetch error',
                        type: ALERT_TYPE_ERROR,
                    });
                }
            };

            fetchTreeData().then(() => {
                resolve();
            });
        });
    }

    const onClick = (e: React.MouseEvent, node: EventDataNode) => {
        navigate('/system/scope/' + node.key);
    };

    return (
        <div className="tree-sidebar">
            <Tree
                onClick={onClick}
                defaultExpandAll={true}
                draggable
                loadData={onLoadData}
                treeData={treeData} />
        </div>
    );
};

export default ScopeSidebar;
