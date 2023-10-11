import { createStore } from "vuex";

const store = createStore({
    state() {
        return {
            isLoading: false,
        };
    },
    mutations: {
        setLoading(state, isLoading) {
            state.isLoading = isLoading;
        },
    },
});

export default store;
