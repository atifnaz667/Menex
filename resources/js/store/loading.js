const state = {
    isLoading: false,
};

const mutations = {
    setLoadingState(state, isLoading) {
        state.isLoading = isLoading;
    },
};

export default {
    namespaced: true,
    state,
    mutations,
};
