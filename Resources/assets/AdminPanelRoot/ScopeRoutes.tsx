/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import {lazy} from "react";

const ScopePage = lazy(() => import("@EveryWorkflow/ScopeBundle/Page/ScopePage"));

export const ScopeRoutes = [
    {
        path: '/system/scope',
        exact: true,
        component: ScopePage
    },
    {
        path: '/system/scope/:code',
        exact: true,
        component: ScopePage
    },
];
