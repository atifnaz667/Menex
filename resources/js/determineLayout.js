function determineLayout(route) {
    // If the route has a 'layout' meta field, return its value
    if (route.meta && route.meta.layout) {
        return route.meta.layout;
    }
    // Default to 'default' layout if no layout is specified
    return "default";
}

export default determineLayout;
