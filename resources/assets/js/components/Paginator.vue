<template>
    <div>

        <nav aria-label="..." v-if="shouldShowPaginator">
            <ul class="pager">
                <li v-if="previousUrl">
                    <a href="#" @click.prevent="currentPage--">Previous</a>
                </li>
                <li v-if="nextUrl">
                    <a href="#" @click.prevent="currentPage++">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    export default {
        props: ['dataSet'],

        data() {
            return {
                currentPage: 1,
                nextUrl: false,
                previousUrl: false,
            }
        },

        watch: {
          dataSet() {
              this.currentPage = this.dataSet.current_page;
              this.previousUrl= this.dataSet.prev_page_url;
              this.nextUrl = this.dataSet.next_page_url;
          },
          currentPage() {
              this.broadcast();
              this.updateUrl();
          }
        },

        computed: {
            shouldShowPaginator() {
                return !!this.nextUrl || !!this.previousUrl;
            }
        },

        methods: {
            broadcast() {
                this.$emit('pageUpdated', this.currentPage);
            },

            updateUrl() {
                history.pushState(null, null, '?page=' + this.currentPage);
            }
        }
    }
</script>